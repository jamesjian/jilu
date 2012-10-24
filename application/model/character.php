<?php

namespace App\Model;

use \App\Model\Base\Character as Base_Character;
use \App\Model\Region as Model_Region;
use \Zx\Model\Mysql;

/**
 * 
 */
class Character extends Base_Character{
    public static function get_characters_by_page_num($page_num = 1, $order_by = 'c.name', $direction = 'ASC') {
        $where = ' 1 ';
        $offset = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $offset, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }
    /**
     * 
     * @param int $character_id
     * @return array ('province_id'=>province_id, 'city_id'=>city_id, 'district_id'=>district_id)
     */
    public static function get_character_region($character_id)
    {
        $character = parent::get_one($character_id);
        $region_id = $character['region_id'];
        $region = Model_Region::get_one($region_id);
        if ($region) {
        switch (intval($region['type']))  {
            case 1: //province
                $arr = array('province_id'=>$region_id, 'city_id'=>0, 'district_id'=>0);
                break;
            case 2: //manipucility
            case 3: //city
                $arr = array('province_id'=>$region['parent_id'], 'city_id'=>$region_id, 'district_id'=>0);
                break;
            case 4: //district
            case 5: //suburb
                $city_id = $region['parent_id'];
                $city = Model_Region::get_one($city_id);
                $arr = array('province_id'=>$city['parent_id'], 'city_id'=>$city_id, 'district_id'=>$region_id);
                break;
        }
        } else {
            $arr= array('province_id'=>0, 'city_id'=>0, 'district_id'=>0);
        }
        return $arr;
    }
}


