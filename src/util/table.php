<?php
declare(strict_types=1);

namespace util;

/*
  Needs a PDO to be set to function
  Fetch style needs to be set on the PDO.
      
  select fields can be either an array [ 'RealDatabaseFieldName', 'AnotherDatabaseFieldName', ... ]
  or a well formated sql string without a SELECT statement.

  where fields can be either an array [ 'RealDatabaseFieldName' => SomeValue, ... ]
  or a well formated sql string without a WHERE statement.
      
*/
  
class table {  
    private const DEFAULT_SELECT = '*';
    private const SELECT = 1;
    private const UPDATE = 2;
    private const INSERT = 3;

    private $_query_type = null;
    private $_table = null;
    private $_select = null;
    private $_where = null;
    private $_query = null;
    private $_pdo_fetch_mode = null;
    private $_pdo = null;
    private $_sql = null;
    private $_records = null;
    private $_fields = null;
    private $_params = null;
    private $_join = null;
    private $_limit = null;
    private $_order_by = null;
    private $_join_tables = null;
    private $_join_types = null;
    
    // $table = RealDatabaseTableName
    public function __construct($table = null,
                                $db_conf = []) {

        if (!$db_conf)
            $db_conf = \system::config()->db();

        $this->_pdo = \util\pdo::init($db_conf);
        $this->_table = $table;
    }

    public function set_pdo($pdo) {
        $this->_pdo = $pdo;
    }

    public function where($fields) {
        self::set_where($fields);
        return $this;
    }

    // Assuming arrays $type contains [ 'LEFT JOIN', 'INNER JOIN' ]
    // Assuming arrays $tables contains [ 'RealTableName', 'RealTableName' ]
    public function join($tables, $types) {
        if (is_array($tables))
            $this->_join_tables = $tables;
        else
            $this->_join_tables = [$tables];

        if (is_array($types))
            $this->_join_types = $types;
        else
            $this->_join_types = [$types];

        return $this;
    }

    // Accepts a well formated join statement
    // Accepts a list of well formated join statements
    public function join_on($join_statements) {
        if (is_array($join_statements))     
            $this->_join = implode(' ', $join_statements);
        else
            $this->_join = $join_statements;
    }
    
    // Assuming argument is a number >= 0
    public function limit(int $limit) {
        self::set_limit($limit);
        return $this;
    }

    // Assuming well formed sql-string or array
    // A well formed sql-string includes the full statement, 'ORDER BY Xxx.Xxx ASC, Xxx.Xxx DESC, Xxx.Xxx ASC'    
    // Assuming array, contains [ 'ASC' => 'RealTableFieldName', 'DESC' => 'AnotherRealTableFieldName', 'AThirdRealTableFieldName' ]
    // It is not mandatory for array to contain sort order key as the third element shows
    public function order($order_by) {
        self::set_order_by($order_by);
        return $this;
    }
    
    // Assuming $fields =  [ 'RealTableFieldName', 'AnotherRealTableFieldName' ]
    // Returns records = [ 0  => [ 'RealTableFieldName' => its value ], .. ]
    // Returns record = [ 'RealTableFieldName' => its value ]
    public function select($fields = null) {
        $this->_query_type = static::SELECT;
        self::create_select_fields_and_params($fields);
        return $this;
    }

    // Shorthand for select() 
    public function get($fields = null) {
        return self::select($fields);
    }
    
    // Assuming $fields =  [ 'RealTableFieldName' => its value ]
    public function update(array $fields = []) {
        if (!$fields)
            return 0;

        $this->_query_type = static::UPDATE;
        self::create_update_fields_and_params($fields);
        return $this;
    }

    // Assuming $fields = [ 'RealTableFieldName' => its value ]
    public function insert(array $fields = []) {
        if (!$fields)
            return 0;

        $this->_query_type = static::INSERT;
        self::create_insert_fields_and_params($fields);
        return $this;
    }

    // Shorthand for insert()
    public function add(array $fields = []) {
        return self::insert();
    }
    
    // Run database query
    public function query(bool $query = true)
    {
        switch ($this->_query_type) {
        case static::SELECT:
            self::create_select_sql();
            if ($query) {
                self::make_query();
                self::set_records();
                return $this->_records;
            }

            break;
        case static::UPDATE:
            self::create_update_sql();
            if ($query) {
                self::make_query();
                return $this->_query->rowCount();
            }

            break;
        case static::INSERT:
            self::create_insert_sql();
            if ($query) {
                self::make_query();
                return $this->_pdo->lastInsertId();
            }

            break;
        default:
            throw new \Exception('Unknown query type', 500);
            break;
        }
        
        self::debug_sql();
        return $this;
    }
    
    // Shorthand for query()
    public function q(bool $query = true) {
        return self::query($query);
    }

    // Some idea, use together with insert()
    public function requery($key_field) {
        // Initial query to insert data
        $id = self::query();

        // Reset fields used in initial query
        self::insert_reset_init();

        // Make new query for recentely inserted data
        self::where([$key_field => $id]);
        self::select();
        return self::query();
    }
    
    private function create_select_fields_and_params($fields) {
        self::select_reset_init();
        if ($fields)
            if (is_array($fields))
                $this->_select = implode(', ', $fields);
            else
                $this->_select = $fields;
    }

    private function select_reset_init() {
        $this->_select = static::DEFAULT_SELECT;
    }
    
    private function create_update_fields_and_params($fields) {
        self::update_reset_init();
        foreach ($fields as $db_field => $value) {
            $value_field = self::get_value_field($db_field);
            $this->_fields[] = sprintf('%s = :%s', $db_field, $value_field);
            $this->_params[$value_field] = $value;
        }
    }

    private function update_reset_init() {
        $this->_fields = [];
        $this->_params = [];
    }
    
    private function create_insert_fields_and_params($fields) {
        self::insert_reset_init();
        foreach ($fields as $db_field => $value) {
            $value_field = self::get_value_field($db_field);
            $this->_fields[] = $db_field;
            $this->_values[] = sprintf(':%s', $value_field);
            $this->_params[$value_field] = $value;
        }
    }

    private function insert_reset_init() {
        $this->_values = [];
        $this->_fields = [];
        $this->_params = [];
    }
    
    private function set_where($where_fields)
    {
        $where = 'WHERE ';
        if (is_array($where_fields)) {
            $field_parts = [];
            foreach ($where_fields as $field => $value) {
                $value_field = self::get_value_field($field);
                $field_parts[] = sprintf('%s = :%s', $field, $value_field);                    
                $this->_params[$value_field] = $value;
            }            
            $where .= implode(' AND ', $field_parts);
        } else
            $where .= $where_fields;

        $this->_where = $where;
    }
    
    private function set_limit($limit)
    {
        if ($limit < 0)
            throw new \Exception('Limit is less than zero', 500);        
        $this->_limit = sprintf('LIMIT %d', $limit);
    }

    private function set_order_by($order_by)
    {
        $sql = $order_by;
        $parts = [];
        if (is_array($order_by)) {
            foreach ($order_by as $order => $field) {
                switch ($order) {
                case 'ASC':
                case 'asc':
                    $part = sprintf('%s ASC', $field);
                    break;
                    
                case 'DESC';
                case 'desc':
                    $part = sprintf('%s DESC', $field);
                    break;
                }
                $parts[] = $part;
            }
            $sql = implode(', ', $parts);
        }
        $this->_order_by = sprintf('ORDER BY %s', $sql);
    }

    // A need to normalize / generate a parameter name
    private function get_value_field($field)
    {
        $field = preg_replace('/[0-9\.]+/', '', $field);
        return sprintf('value_%s_field', strtolower($field));
    }

    private function make_query()
    {
        if (!$this->_query = $this->_pdo->prepare($this->_sql))
            throw new \Exception('Failed to prepare query', 500);
        if (!$this->_query->execute($this->_params))
            throw new \Exception('Failed to execute query', 500);
    }

    private function set_records()
    {
        $this->_records = [];
        while ($record = $this->_query->fetch())
            $this->_records[] = $record;            

        switch (count($this->_records)) {
        case 1:
            $this->_records = $this->_records[0];
            break;
        }
    }    
    
    private function create_update_sql()
    {        
        $this->_sql = sprintf( 'UPDATE %s SET %s %s',
                               $this->_table,
                               implode(', ', $this->_fields),
                               $this->_where );
    }

    private function create_insert_sql()
    {
        $this->_sql = sprintf( 'INSERT INTO %s ( %s ) VALUES ( %s )',
                               $this->_table,
                               implode(', ', $this->_fields),
                               implode(', ', $this->_values));
    }
    
    private function create_select_sql()
    {
        $this->_sql = sprintf( 'SELECT %s FROM %s %s %s %s %s',
                               $this->_select,
                               $this->_table,
                               $this->_join,
                               $this->_where,
                               $this->_order_by,
                               $this->_limit );
    }

    public function get_where() { return $this->_where; }
    public function get_records() { return $this->_records; }

    public function debug_sql($lb = "\n") {
        self::echo_sql($lb);
        echo $lb;
        self::echo_params($lb);
        echo $lb;
    }
    
    public function echo_params($lb) {
        echo "Parameters:\n";
        echo \util\debug::as_string($this->_params, $lb);
        return $this;
    }
    
    public function echo_sql($lb) {
        echo \util\debug::sql($this->_sql, $lb);
        return $this;
    }
}
