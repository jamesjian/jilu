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
     *contain municipalitys
     */
    public static function get_provinces()
   {
   }
   /**
    * contain districts and counties
    */
   public static function get_districts_by_city_id($city_id)
   {
   }
   public static function get_cities_by_province_id($province_id){
       
   }
   /**
    * 
    * @param type $district_id contain county
    */
   public static function get_city_by_district_id($district_id)
   {
       
   }
   /**
    * 
    * @param int $city_id 
    */
   public static function get_province_by_city_id($city_id)
   {
       
   }
}


