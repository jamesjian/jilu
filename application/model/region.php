<?php

namespace App\Model;

use \App\Model\Base\Region as Base_Region;
use \Zx\Model\Mysql;

/**
 * 
 * 3 levels: province-city-district/county
 * 2 levels: municipality->district/county
 */
class Region extends Base_Region{

    

    /**
     *NOT contain municipalitys
     */
    public static function get_provinces($where='1', $order_by='r.name', $direction='ASC')
   {
        $where = "r.type=1 AND ($where)" ;
        return parent::get_all($where, 0, MAXIMUM_ROWS, $order_by, $direction);
   }
    /**
     *ONLY contain municipalities
     */
    public static function get_municipalities()
   {
        $where = 'r.type=2';
        $order_by = 'r.name';
        $direction = 'ASC';
        return parent::get_all($where, 0, MAXIMUM_ROWS, $order_by, $direction);
   }
   
   /**
    * contain districts and counties
    */
   public static function get_districts_by_city_id($city_id)
   {
        $where = 'r.type=4 OR r.type=5 AND r.parent_id=' . $city_id;
        $order_by = 'r.name';
        $direction = 'ASC';
        return parent::get_all($where, 0, MAXIMUM_ROWS, $order_by, $direction);       
   }
   public static function get_cities_by_province_id($province_id){
               $where = 'r.type=3 AND r.paretn+id=' . $province_id;
        $order_by = 'r.name';
        $direction = 'ASC';
        return parent::get_all($where, 0, MAXIMUM_ROWS, $order_by, $direction);
   }
   /**
    * 
    * @param type $district_id can be district or county
    */
   public static function get_city_by_district_id($district_id)
   {
       $district = parent::get_one($district_id);
       if ($district['type'] == '4' || $district['type'] == '5') {
           $city_id = $district['parent_id'];
           $city = parent::get_one($city_id);
           return $city;
       } 
       return false;
   }
   /**
    * 
    * @param int $city_id 
    * @return province can be municipality
    */
   public static function get_province_by_city_id($city_id)
   {
       $district = parent::get_one($district_id);
       if ($district['type'] == '4' || $district['type'] == '5') {
           $city_id = $district['parent_id'];
           $city = parent::get_one($city_id);
           return $city;
       } 
       return false;       
   }
}


