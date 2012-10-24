<?php

namespace App\Module\Mem\Controller;

use \App\Model\Character as Model_Character;
use \App\Transaction\Character as Transaction_Character;
use \Zx\View\View;
use \Zx\Test\Test;

//currently one author, one book, one character
class Character extends Mem {

    public $list_page = '';

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/mem/view/character/';
        $this->list_page = MEM_HTML_ROOT . 'character/retrieve/1/name/ASC/';
        \App\Transaction\Session::set_ck_upload_path('book');
        parent::init();
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : '';
            $province_id = $city_id = $district_id = 0; //initialize
            $province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : 0;
            if ($province_id != 0) {
                $city_id = isset($_POST['city_id']) ? intval($_POST['city_id']) : 0;
                if ($city_id != 0) {
                    $district_id = isset($_POST['district_id']) ? intval($_POST['district_id']) : 0;
                }
            }
            if ($name <> '') {
                $arr = array('name' => $name,
                    'birthday' => $birthday,
                    'province_id' => $province_id,
                    'city_id' => $city_id,
                    'district_id' => $district_id,
                );
                if (Transaction_Character::create_character($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $provinces = Model_Region::get_provinces();
            $cities = array();  //empty
            $districts = array(); //empty
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('provinces', $provinces);
            View::set_action_var('cities', $cities);
            View::set_action_var('districts', $districts);
        }
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Character::delete_character($id);
        header('Location: ' . $this->list_page);
    }

    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            \Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['name']))
                    $arr['name'] = trim($_POST['name']);
                if (isset($_POST['province_id']))
                    $arr['province_id'] = intval($_POST['province_id']);
                if (isset($_POST['city_id']))
                    $arr['city_id'] = intval($_POST['city_id']);
                if (isset($_POST['district_id']))
                    $arr['district_id'] = intval($_POST['district_id']);
                if (isset($_POST['birthday']))
                    $arr['birthday'] = trim($_POST['birthday']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Character::update_character($id, $arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            if (!isset($id)) {
                $id = $this->params[0];
            }
            $character = Model_Character::get_one($id);
            $character_region = Model_Character::get_character_region($id);
            $provinces = Model_Region::get_provinces();
            $province_id = $character_region['provice_id']; //initial province id
            $cities = Model_Region::get_cities_by_province_id($province_id);
            $city_id = $character_region['city_id']; //initial city id
            $districts = Model_Region::get_districts_by_city_id($city_id);
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('character', $character);
            View::set_action_var('character_region', $character_region);
            View::set_action_var('provinces', $provinces);
            View::set_action_var('cities', $cities);
            View::set_action_var('districts', $districts);
        }
    }

    /*
      public function search() {
      if (isset($_POST['search']) && trim($_POST['search']) != '') {
      $link = $this->list_page . trim($_POST['search']);
      } else {
      $link = $this->list_page;
      }
      header('Location: ' . $link);
      }
     * 
     */

    /**
      only one character for one author currently
     */
    public function retrieve() {
        \App\Transaction\Session::remember_current_mem_page();
        \App\Transaction\Session::set_mem_current_l1_menu('Character');
        $character = Model_Character::get_character_by_author_id($this->user['id']);
        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('character', $character);
    }

}
