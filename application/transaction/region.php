<?php

namespace App\Transaction;

use \App\Model\Region as Model_Region;
use \Zx\Message\Message;
use \App\Transaction\Cache;

class Region {

    /**
     * from cache 
     */
    public static function get_city_and_suburb_option_list_by_state_name($state) {
        if (!Model_Region::valid_state($state)) {
            $state = 'NSW';
        }
        $cache_id = $state . '_city_and_suburb_option_list';
        $str = Cache::get($cache_id);
        if ($str == NO_CACHE_DATA) {
            $city_list = Model_Region::get_cities_by_state($state);
            $suburb_list = Model_Region::get_suburbs_by_state($state);
            $str_city = '';
            $str_suburb = '';
            if (count($city_list) > 0) {
                foreach ($city_list as $city) {
                    $str_city .= "<option value='" . $city->id . "'>" . $city->region_name_en . '(' . $city->postcode . ")</option>";
                }
            }
            if (count($suburb_list) > 0) {
                foreach ($suburb_list as $suburb) {
                    $str_suburb .= "<option value='" . $suburb->id . "'>" . substr($suburb->region_name_en, 0, 30) .'('. $suburb->postcode . ")</option>";
                }
            }
            $arr = array(
                'city_list' => $str_city,
                'suburb_list' => $str_suburb);

            $str = serialize($arr);
            App_Cache::set($cache_id, $str);  //save to cache table
        } else {
            $arr = unserialize($str);
        }
        return $arr;
    }

    /**
     *  for frontend change region dialog form, contain cities and suburbs
     * @param string $state_name      
     * @return for <select></select> 
     * html string <option value='region id'>suburb(postcode)</option>......<option value=''>XXXn</option>
     * currently, we are not using cache in test, 
     * in the future, we will use cache
     */
    public static function get_state_region_option_list($state) {
        $state_name = strtoupper($state);
        //don't delete the following lines
        //$cache_id = 'search_bar_' . $state_name . '_region_option_list';
        //$region_option_list = App_Cache::get($cache_id);
        $region_option_list = NO_CACHE_DATA;
        if ($region_option_list == NO_CACHE_DATA) {
            $cities = Model_Region::get_cities_by_state($state_name);
            $suburbs = Model_Region::get_suburbs_by_state($state_name);
            $list = "<option value='0'>所有地区（市或区）</option>";
            if ($cities->count() > 0 || $suburbs->count() > 0) {
                if ($cities->count() > 0) {
                    foreach ($cities as $city) {
                        $list .= "<option value='{$city->id}'>{$city->region_name_en} ({$city->postcode})</option>";
                    }
                    $list .= "<option value='0'>--------------</option>";
                }
                if ($suburbs->count() > 0) {
                    foreach ($suburbs as $suburb) {
                        $list .= "<option value='{$suburb->id}'>{$suburb->region_name_en} ({$suburb->postcode})</option>";
                    }
                }
            }
            //don't delete the following line
            //App_Cache::set($cache_id, $list);  //save to cache table
        }
        return $list;
    }

    /**
     * it's a wrong method
     * @param string $region_name may be state or city
     * @return boolean
     */
    public static function change_region1($region_name) {
        $current_region = array('state' => 'NSW',
            'city' => '', 'city_id' => 0,
            'suburb' => '', 'suburb_id' => 0); //default region
        if (array_key_exists($region_name, Model_Region::get_state_list())) {
            //if state
            $current_region['state'] = $region_name;
            $current_region['state'] = Model_Region::get_id_by_state_name($region_name);
        } elseif (array_key_exists($region_name, Model_Region::get_all_cities())) {
            //if city
            $current_region['city'] = $region_name;
            $current_region['city_id'] = Model_Region::get_id_by_city_name($region_name);
            $current_region['state'] = Model_Region::get_state_name_by_city_name($region_name);
        } else {
            //if suburb, region name contain suburb_id and suburb_name for validation
            $suburb_id = intval(substr($region_name, 0, 5)); //0<id<17000
            $suburb_name = substr($region_name, 5); //use "-" to replace ' '
            if (Model_Region::corret_suburb($suburb_id, $suburb_name)) {
                $current_region['suburb'] = $suburb_name;
                $current_region['suburb_id'] = $suburb_id;
                $current_region['state'] = Model_Region::get_state_name_by_suburb_id($suburb_id);
            }
        }
        App_Session::set_session('current_region', $current_region);
        return true;
    }

    /**
     * from change region dialog in the front end, 'ALL' is default state
     * @param string $state can be 'ALL'
     * @param int  $region_id  can be 0, can be city or suburb id
     */
    public static function change_region($state, $region_id) {
        $current_region = array('state' => 'ALL',
            'city' => '', 'city_id' => 0,
            'suburb' => '', 'suburb_id' => 0); //default region
        if (in_array($state, Model_Region::get_state_all_abbr())) {
            //if state
            $current_region['state'] = $state;
            if ($state != 'ALL') {
                $region = Model_Region::get_record($region_id);
                if ($region->state == $state) {
                    if ($region->type == 2) {
                        //city
                        $current_region['city'] = $region->region_name_en;
                        $current_region['city_id'] = $region_id;
                    }
                    if ($region->type == 3) {
                        //suburb
                        $current_region['suburb'] = $region->region_name_en;
                        $current_region['suburb_id'] = $region_id;
                    }
                } else {
                    //wrong city and suburb, ignore city and suburb
                }
            } else {
                //any region, ignore city and suburb
            }
        } else {
            //wrong state, ignore it
        }
        App_Session::set_session('current_region', $current_region);
        return true;
    }

    /**
     * state='ALL' means any region in Australia
     * @return int 
     */
    public static function get_current_region() {
        $current_region = App_Session::get_session('current_region', false);
        if (!$current_region) {
            $current_region = array('state' => 'ALL',
                'city' => '', 'city_id' => 0,
                'suburb' => '', 'suburb_id' => 0);            //default value is NSW
            self::set_current_region($current_region);
        }
        return $current_region;
    }

    /**
     * for header 当前地区
     * @return string 
     */
    public static function get_current_region_string() {
        $current_region = self::get_current_region();
        $str = '';
        if ($current_region['state'] != '')
            $str .= $current_region['state'];
        if ($current_region['city'] != '')
            $str .= '  ' . $current_region['city'];
        if ($current_region['suburb'] != '')
            $str .= '  ' . $current_region['suburb'];
        return $str;
    }

    /**
     * 
     * @param arr $region_arr 
     * array('state'=>'NSW',
      'city'=>'sydney', 'city_id'=>7,
      'suburb'=>'ashfield', 'suburb_id'=>10));
     * @return true
     */
    public static function set_current_region($region_arr) {
        App_Session::set_session('current_region', $region_arr);
        return true;
    }

    /**
     * when using search bar, store region information into session as "search_region"
     * make sure all states, cities or suburbs are valid, if not valid, fill in an empty value
     * @param string $state can be "ALL"
     * @param  $region region might be city (value start from 'c') or 
     *               suburb(value start from 's')
     * @return current region array
     */
    public static function change_search_region($state, $region_id) {
        //App_Test::objectLog('$posted','11111111', __FILE__, __LINE__, __CLASS__, __METHOD__);
        //if no state, then no city or suburb, if state is 'ALL', then no city or suburb
        $region_arr = array('state' => 'ALL',
            'city' => '', 'city_id' => 0,
            'suburb' => '', 'suburb_id' => 0);
        $state_arr = Model_Region::get_state_all_abbr();

        if (in_array($state, $state_arr)) {
            //if valid state
            $region_arr['state'] = $state;
            $region = Model_Region::get_record($region_id);
            if ($region && $region->state == $state) {
                //valid region 
                if ($region->type == 2) {
                    //city
                    $region_arr['city'] = $region->region_name_en;
                    $region_arr['city_id'] = $region_id;
                }

                if ($region->type == 3) {
                    //suburb
                    $region_arr['suburb'] = $region->region_name_en;
                    $region_arr['suburb_id'] = $region_id;
                }
            } else {
                //invalid region, or state is "ALL", no need to set city/suburb
            }
        } else {
            //invalid state, ignore it
        }
        self::set_search_region($region_arr);
        return $region_arr;
    }

    /**
     * 
     * @param arr $region_arr 
     * array('state'=>'NSW',  
      'city'=>'sydney', 'city_id'=>7,
      'suburb'=>'ashfield', 'suburb_id'=>10));
     * state can be 'ALL', city/suburb can be empty
     * @param string $state
     * @param int $region_id
     * @return true
     */
    public static function set_search_region($state, $region_id) {
        $region_arr = array('state' => 'ALL',
            'city' => '', 'city_id' => 0,
            'suburb' => '', 'suburb_id' => 0);
        $states_arr = Model_Region::get_state_all_abbr();
        if (in_array($state, $states_arr)) {
            $region_arr['state'] = $state;
        } else {
            $region_arr['state'] = 'ALL';
        }
        $region = Model_Region::get_record($region_id);
        if ($region) {
            if ($region->type == 2) {
                $region_arr['city_id'] = $region_id;
                $region_arr['city'] = $region->region_name_en;
            }
            if ($region->type == 3) {
                $region_arr['suburb_id'] = $region_id;
                $region_arr['suburb'] = $region->region_name_en;
            }
        }
        App_Session::set_session('search_region', $region_arr);
        return $region_arr;
    }

    public static function get_search_region() {
        $search_region = App_Session::get_session('search_region', false);
        if (!$search_region) {
            $search_region = array('state' => 'ALL',
                'city' => '', 'city_id' => 0,
                'suburb' => '', 'suburb_id' => 0);            //default value is 'ALL'
            self::set_search_region($search_region);
        }
        return $search_region;
    }

    /**
     * 
     * @param type $state_name
     * @return array of mysql result ojbects
     */
    public static function get_state_postcodes($state_name) {
        $state_name = strtoupper($state_name);
        $states = Model_Region::get_all_states();
        if (!in_array($state_name, $states))
            $state_name = 'VIC';
        $cache_id = $state_name . '_postcodes';
        $postcodes_arr = App_Cache::get($cache_id);
        if ($postcodes_arr == NO_CACHE_DATA) {
            $postcodes = Model_Region::get_state_postcodes($state_name);
            $postcodes_arr = array();
            foreach ($postcodes as $postcode) {
                $arr = array('id' => $postcode->id, 'state' => $postcode->state, 'suburb' => $postcode->suburb,
                    'postcode' => $postcode->postcode);
                $postcodes_arr[] = $arr;
            }


            App_Cache::set($cache_id, $postcodes_arr);  //save to cache table
        }
        //App_Test::objectLog('$postcode_arr',$postcodes_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return $postcodes_arr;
    }

    /**
     * @param array $arr
     * @return boolean true or false 
     */
    public static function create_suburb($arr) {
        if (($suburb_id = Model_Region::exist_suburb($arr['postcode'], $arr['region_name_en'])) === false) {
            $arr['type'] = 3; //suburb is 3
            if (Model_Region::create_record($arr)) {
                App_Session::set_success_message("Suburb is created successfully.");
                return true;
            } else {
                App_Session::set_error_message("Suburb cannot be saved, contact administrator.");
                return false;
            }
        } else {
            App_Session::set_error_message("This suburb exists, cannot be created twice.");
            return false;
        }
    }

    /**
     * when suburb is updated, all cache tables will be updated (state, postcode and suburb name) 
     * @param integer $suburb_id
     * @param array $arr
     * @return boolean 
     */
    public static function update_suburb($suburb_id, $arr) {
        if (!Model_Region::duplicate_suburb($suburb_id, $arr['postcode'], $arr['region_name_en'])) {
            if (Model_Region::update_record($suburb_id, $arr)) {
                //todo update all cache tables  

                App_Session::set_success_message("Suburb is updated successfully.");
                return true;
            } else {
                App_Session::set_error_message("Suburb cannot be saved, contact administrator.");
                return false;
            }
        } else {
            App_Session::set_error_message("Suburb cannot be duplicate, contact administrator.");
        }
    }

    /**
     * return boolean
     */
    public static function delete_suburb($suburb_id) {
        if (Model_Region::delete_record($suburb_id)) {
            App_Session::set_success_message("Suburb is deleted successfully.");
            return true;
        } else {
            App_Session::set_success_message("Suburb is deleted successfully.");
            return false;
        }
    }

    /**
     * the bigger, the more important
     * down: will become smaller
     * swap weight with the closest lower (weight) category
     * @param integer $category_id
     * @return boolean
     */
    public static function down_suburb($suburb_id) {
        $suburb = Model_Region::get_suburb($suburb_id);
        $display_order = $suburb->display_order;
        $where = " p.state='{$suburb->state}'";
        $suburbs = Model_Region::get_records(0, MAXIMUM_ROWS, 'display_order', 'ASC', $where);
        $lower_suburb_id = 0;
        foreach ($suburbs as $suburb) {
            if ($suburb->id == $suburb_id) {
                break;
            } else {
                $lower_suburb_id = $suburb->id;
                $lower_suburb_display_order = $suburb->display_order;
            }
        }
        if ($lower_suburb_id != 0) {
            //find a suburb which is smaller than $suburb_id, swap them
            $arr = array('display_order' => $display_order);
            Model_Region::update_record($lower_suburb_id, $arr);
            $arr = array('display_order' => $lower_suburb_display_order);
            Model_Region::update_record($suburb_id, $arr);
            return true;
        } else {
            return false;
        }
    }

    /**
     * the bigger, the more important
      up: will become bigger
     * swap weight with the closest upper (weight) category
     * @param integer $category_id
     * @return boolean
     */
    public static function up_suburb($suburb_id) {
        $suburb = Model_Region::get_suburb($suburb_id);
        $display_order = $suburb->display_order;
        $where = " p.state='{$suburb->state}'";
        $suburbs = Model_Region::get_records(0, MAXIMUM_ROWS, 'display_order', 'DESC', $where);
        $upper_suburb_id = 0;
        foreach ($suburbs as $suburb) {
            if ($suburb->id == $suburb_id) {
                break;
            } else {
                $upper_suburb_id = $suburb->id;
                $upper_suburb_display_order = $suburb->display_order;
            }
        }
        if ($upper_suburb_id != 0) {
            //find a suburb which is bigger than $suburb_id, swap them
            $arr = array('display_order' => $display_order);
            Model_Region::update_record($upper_suburb_id, $arr);
            $arr = array('display_order' => $upper_suburb_display_order);
            Model_Region::update_record($suburb_id, $arr);
            return true;
        } else {
            return false;
        }
    }

    public static function change_status($suburb_id, $status) {
        $arr = array('status' => $status);
        return Model_Region::update_record($suburb_id, $arr);
    }

    public static function change_is_selected($suburb_id, $is_selected) {
        $arr = array('is_selected' => $is_selected);
        return Model_Region::update_record($suburb_id, $arr);
    }

    /**
     * when some new company-suburb relationship occurs, and this suburb is not in suburb table, insert it
     */
    public static function refresh_suburbs() {
        $q = "SELECT * FROM  `company` 
                WHERE suburb NOT IN (SELECT suburb FROM suburb)";
        $query = DB::query(Database::SELECT, $q);
        $suburbs = $query->as_object()->execute();  //if return array $users = $q->execute()->as_array();
        foreach ($suburbs as $suburb) {
            $arr = array('suburmb' => $suburb->suburb, 'state' => $suburb->state, 'postcode' => $suburb->postcode,
                'is_selected' => 0, 'status' => 1, 'display_order' => 0);
            Model_Region::create_record($arr);
        }
        return true;
    }

    /**
     * for <select></select> 
     * @param type $state_name
     * @return html string <option value='postcode id'>suburb(postcode)</option>......<option value=''>XXXn</option>
     */
    public static function get_state_postcode_option_list1($state_name) {
        $state_name = strtoupper($state_name);
        $cache_id = $state_name . '_postcode_option_list';
        $postcode_option_list = App_Cache::get($cache_id);
        if ($postcode_option_list == NO_CACHE_DATA) {
            $postcodes = Model_Region::get_state_postcodes($state_name);
            $postcode_option_list = '';
            foreach ($postcodes as $postcode) {
                $postcode_option_list .= "<option value='{$postcode->id}'>{$postcode->suburb}({$postcode->postcode})</option>";
            }
            //App_Test::objectLog('$postcode_option_list',$postcode_option_list, __FILE__, __LINE__, __CLASS__, __METHOD__);
            App_Cache::set($cache_id, $postcode_option_list);  //save to cache table
        }
        return $postcode_option_list;
    }

    /**
     * 
     * @param type $state_name
     * @return array of mysql result ojbects
     */
    public static function get_state_postcodes1($state_name) {
        $state_name = strtoupper($state_name);
        $states = Model_Region::get_all_states();
        if (!in_array($state_name, $states))
            $state_name = 'VIC';
        $cache_id = $state_name . '_postcodes';
        $postcodes_arr = App_Cache::get($cache_id);
        if ($postcodes_arr == NO_CACHE_DATA) {
            $postcodes = Model_Region::get_state_postcodes($state_name);
            $postcodes_arr = array();
            foreach ($postcodes as $postcode) {
                $arr = array('id' => $postcode->id, 'state' => $postcode->state, 'suburb' => $postcode->suburb,
                    'postcode' => $postcode->postcode);
                $postcodes_arr[] = $arr;
            }


            App_Cache::set($cache_id, $postcodes_arr);  //save to cache table
        }
        //App_Test::objectLog('$postcode_arr',$postcodes_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return $postcodes_arr;
    }

    /**
     * contain "All" option, but 'NSW' is a default option
     */
    public static function get_searchbar_state_select_list() {
        $states = Model_Region::get_all_states();
        $list = "<li value='ANY'>All</li>
                 <li value='NSW' selected>New South Wales</li>
                <li value='VIC'>Victoria</li>
                 <li value='ACT'>Australian Capital Territory</li>
                 <li value='NT'>Northern Territory</li>
                <li value='QLD'>Queensland</li>
                <li value='SA'>South Australia</li>
                <li value='TAS'>Tasmania</li>
                <li value='WA'>Western Australia</li>";
        return $list;
    }

    /**
     * for <select></select> 
     * @param type $state_name
     * @return html string <option value='region id'>city(postcode)</option>......<option value=''>XXXn</option>
     */
    public static function get_state_city_option_list($state_name) {
        $state_name = strtoupper($state_name);
        $cache_id = $state_name . '_city_option_list';
        $city_option_list = App_Cache::get($cache_id);
        if ($city_option_list == NO_CACHE_DATA) {
            $cities = Model_Region::get_city_in_state_records($state_name);
            $city_option_list = '<option value="0">任意中心城市</option>';
            foreach ($cities as $city) {
                $city_option_list .= "<option value='{$city->id}'>{$city->region_name_en}({$city->postcode})</option>";
            }
            //App_Test::objectLog('$postcode_option_list',$postcode_option_list, __FILE__, __LINE__, __CLASS__, __METHOD__);
            App_Cache::set($cache_id, $city_option_list);  //save to cache table
        }
        return $city_option_list;
    }

    /**
     * for <select></select> 
     * @param type $state_name
     * @return html string <option value='region id'>suburb(postcode)</option>......<option value=''>XXXn</option>
     */
    public static function get_state_suburb_option_list($state_name) {
        $state_name = strtoupper($state_name);
        $cache_id = $state_name . '_suburb_option_list';
        $suburb_option_list = App_Cache::get($cache_id);
        if ($suburb_option_list == NO_CACHE_DATA) {
            $suburbs = Model_Region::get_suburb_in_state_records($state_name);
            $suburb_option_list = '<option value="0">请选择区(suburb)</option>';
            foreach ($suburbs as $suburb) {
                $suburb_option_list .= "<option value='{$suburb->id}'>{$suburb->region_name_en}({$suburb->postcode})</option>";
            }
            //App_Test::objectLog('$postcode_option_list',$postcode_option_list, __FILE__, __LINE__, __CLASS__, __METHOD__);
            App_Cache::set($cache_id, $suburb_option_list);  //save to cache table
        }
        return $suburb_option_list;
    }

}
