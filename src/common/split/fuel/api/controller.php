<?php
declare(strict_types=1);
namespace common\split\fuel\api;

class controller extends \api\resource\controller {

    public function per_traveler() {
        $cost_data = $this->_json_data;
        
        $distance_km = $cost_data['distance_km'];
        $fuel_cost = $cost_data['fuel_cost'];
        $consumption_100km = $cost_data['vehicle_consumption_100km'];

        $total = $distance_km * $fuel_cost * $consumption_100km;
        $total = $total * 0.01;

        $traveler_count = $cost_data['traveler_count'];
        $cost_per_traveler = $total / $traveler_count;
        $cost_per_traveler = round($cost_per_traveler, 2);
        return [ 'cost_per_traveler' => $cost_per_traveler ];
    }   
}
