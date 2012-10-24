<?php

namespace App\Module\Front\Controller;

use \Zx\View\View;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Region as Model_Region;

/**
 * homepage: /=>/front/region/latest/page/1
 * latest: /front/region/latest/page/3
 * most popular:/front/region/most_popular/page/3
 * region under category: /front/regioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/region/content/$id/$region_url
 * keyword: /front/region/keyword/$keyword_3
 */
class Region extends Front {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/region/';
        parent::init();
    }
   
   
    public function action_get_city_and_suburb_option_list_by_state_name()
    {
        $state = (isset($_POST['state'])) ?  trim($_POST['state']): 'NSW';
        $arr = App_Region::get_city_and_suburb_option_list_by_state_name($state);
        $view = View::factory($this->view_path . 'city_and_suburb_list_by_state_name_ajax');
        $view->set('arr', $arr);
        $this->ajax_view($view);  
    }
    public function action_get_state_suburb_option_list_by_state_name()
    {

        $state_name = $this->request->param('id', 'ACT');  //STATE NAME
        $state_suburb_option_list = App_Region::get_state_suburb_option_list($state_name);
              //App_Test::objectLog('$state_postcode_option_list',$state_postcode_option_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        $view = View::factory($this->view_path . 'state_suburb_option_list');
        $view->set('state_suburb_option_list', $state_suburb_option_list);
        $this->ajax_view($view);
    }          
}
