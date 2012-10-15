<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Html as Transaction_Html;
use \App\Model\User as Model_User;
use \App\Model\Usercategory as Model_Usercategory;

/**
 * homepage: /=>/front/user/latest/page/1
 * latest: /front/user/latest/page/3
 * most popular:/front/user/most_popular/page/3
 * user under category: /front/usercategory/retrieve/$category_id_3/category_name.php
 * one: /front/user/content/$id/$user_url
 * keyword: /front/user/keyword/$keyword_3
 */
class User extends Front {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/user/';
        parent::init();
    }

    /*     * one user
     * /front/user/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $user_url = $this->params[0]; //it's url rather than an id

        $user = Model_User::get_one_by_url($user_url);
        //\Zx\Test\Test::object_log('$user', $user, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($user) {
            $user_id = $user['id'];
            Transaction_Html::set_title($user['title']);
            Transaction_Html::set_keyword($user['keyword'] . ',' . $user['keyword_en']);
            Transaction_Html::set_description($user['title']);
            Model_User::increase_rank($user_id);

            View::set_view_file($this->view_path . 'one_user.php');
            $relate_users = Model_User::get_10_active_related_users($user_id);
            View::set_action_var('user', $user);
            View::set_action_var('related_users', $relate_users);
        } else {
            //if no user, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/user/keyword/$keyword/page/3, 3 is page number
     */
    public function keyword() {
        $keyword = (isset($this->params[0])) ? $this->params[0] : '';
        if ($keyword == '') {
            //goto homepage
            Transaction_Html::goto_home_page();
        } else {
            $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
            $order_by = 'rank';
            $direction = 'DESC';
            $users = Model_User::get_active_users_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$users', $users, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_users = Model_User::get_num_of_active_users_by_keyword($keyword);
            $num_of_pages = ceil($num_of_users / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            View::set_action_var('keyword', $keyword);
            View::set_action_var('users', $users);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve users under a category
      front/user/category/auzhoubaoxian/page/3, 5 is cat id, 3 is page number
      $params[0] = auzhoubaoxian, $params[1] = 'page', $params[2] = 3;
     */
    public function category() {
        $cat_title = (isset($this->params[0])) ? $this->params[0] : '';
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($cat_title != '' && $cat = Model_Usercategory::exist_cat_title($cat_title)) {

            //$cat = Model_Usercategory::get_one($cat_id);
            Transaction_Html::set_title($cat['title']);
            Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
            Transaction_Html::set_description($cat['title']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $users = Model_User::get_active_users_by_cat_id_and_page_num($cat['id'], $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$users', $users, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_users = Model_User::get_num_of_active_users_by_cat_id($cat['id']);
            $num_of_pages = ceil($num_of_users / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
            View::set_action_var('cat', $cat);
            View::set_action_var('users', $users);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        } else {
            //if invalid category
            // \Zx\Test\Test::object_log('$cat_title', 'no', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Transaction_Html::goto_home_page();
        }
    }

    /**
      user/latest/3, 3 is page number, if missing, 1 is default page number
     * including home page
     */
    public function latest() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('latest');
        Transaction_Html::set_keyword('latest');
        Transaction_Html::set_description('latest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $users = Model_User::get_active_users_by_page_num($current_page, $order_by, $direction);
        $num_of_users = Model_User::get_num_of_active_users();
        $num_of_pages = ceil($num_of_users / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_latest.php');
        View::set_action_var('users', $users);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      user/hottest/3, 3 is page number, if missing, 1 is default page number
     */
    public function hottest() {
        Transaction_Html::set_title('hottest');
        Transaction_Html::set_keyword('hottest');
        Transaction_Html::set_description('hottest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'rank';
        $direction = 'DESC';
        $users = Model_User::get_active_users_by_page_num($current_page, $order_by, $direction);
        $num_of_users = Model_User::get_num_of_active_users();
        $num_of_pages = ceil($num_of_users / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
        View::set_action_var('users', $users);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
