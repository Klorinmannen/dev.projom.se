<?php
declare(strict_types=1);

namespace user;

class model {
    
    public static function add(array $fields) {
        $table = new \util\table('User');
        return $table->insert($fields)->requery('UserID');
    }

    public static function search_by_username(string $username) {
        $table = new \util\table('User');
        return $table->select(['UserID', 'Password', 'Name', 'Email'])->where([ 'Name' => $username, 'Deleted' => 0 ])->query();       
    }            

    public static function update_user(int $user_id, array $fields) {
        $table = new \util\table('User');
        return $table->update($fields)->where([ 'UserID' => $user_id ])->query();
    }

    public static function get_user(int $user_id): array {
        $table = new \util\table('User');
        return $table->select()->where([ 'UserID' => $user_id ])->query();
    }
}
