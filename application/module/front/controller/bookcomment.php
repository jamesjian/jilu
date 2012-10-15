<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Bookcomment as Model_Bookcomment;
use \App\Model\Bookcommentcategory as Model_Bookcommentcategory;

/**
 * homepage: /=>/front/bookcomment/latest/page/1
 * latest: /front/bookcomment/latest/page/3
 * most popular:/front/bookcomment/most_popular/page/3
 * bookcomment under category: /front/bookcommentcategory/retrieve/$category_id_3/category_name.php
 * one: /front/bookcomment/content/$id/$bookcomment_url
 * keyword: /front/bookcomment/keyword/$keyword_3
 */
class Bookcomment extends Front {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/bookcomment/';
        parent::init();
    }

    /*     * one bookcomment
     * /front/bookcomment/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $bookcomment_url = $this->params[0]; //it's url rather than an id

        $bookcomment = Model_Bookcomment::get_one_by_url($bookcomment_url);
        //\Zx\Test\Test::object_log('$bookcomment', $bookcomment, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($bookcomment) {
            $bookcomment_id = $bookcomment['id'];
            Transaction_Html::set_title($bookcomment['title']);
            Transaction_Html::set_keyword($bookcomment['keyword'] . ',' . $bookcomment['keyword_en']);
            Transaction_Html::set_description($bookcomment['title']);
            Model_Bookcomment::increase_rank($bookcomment_id);

            View::set_view_file($this->view_path . 'one_bookcomment.php');
            $relate_bookcomments = Model_Bookcomment::get_10_active_related_bookcomments($bookcomment_id);
            View::set_action_var('bookcomment', $bookcomment);
            View::set_action_var('related_bookcomments', $relate_bookcomments);
        } else {
            //if no bookcomment, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/bookcomment/keyword/$keyword/page/3, 3 is page number
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
            $bookcomments = Model_Bookcomment::get_active_bookcomments_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$bookcomments', $bookcomments, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_bookcomments = Model_Bookcomment::get_num_of_active_bookcomments_by_keyword($keyword);
            $num_of_pages = ceil($num_of_bookcomments / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            View::set_action_var('keyword', $keyword);
            View::set_action_var('bookcomments', $bookcomments);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve bookcomments under a category
      front/bookcomment/category/auzhoubaoxian/page/3, 5 is cat id, 3 is page number
      $params[0] = auzhoubaoxian, $params[1] = 'page', $params[2] = 3;
     */
    public function category() {
        $cat_title = (isset($this->params[0])) ? $this->params[0] : '';
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($cat_title != '' && $cat = Model_Bookcommentcategory::exist_cat_title($cat_title)) {

            //$cat = Model_Bookcommentcategory::get_one($cat_id);
            Transaction_Html::set_title($cat['title']);
            Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
            Transaction_Html::set_description($cat['title']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $bookcomments = Model_Bookcomment::get_active_bookcomments_by_cat_id_and_page_num($cat['id'], $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$bookcomments', $bookcomments, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_bookcomments = Model_Bookcomment::get_num_of_active_bookcomments_by_cat_id($cat['id']);
            $num_of_pages = ceil($num_of_bookcomments / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
            View::set_action_var('cat', $cat);
            View::set_action_var('bookcomments', $bookcomments);
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
      bookcomment/latest/3, 3 is page number, if missing, 1 is default page number
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
        $bookcomments = Model_Bookcomment::get_active_bookcomments_by_page_num($current_page, $order_by, $direction);
        $num_of_bookcomments = Model_Bookcomment::get_num_of_active_bookcomments();
        $num_of_pages = ceil($num_of_bookcomments / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_latest.php');
        View::set_action_var('bookcomments', $bookcomments);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      bookcomment/hottest/3, 3 is page number, if missing, 1 is default page number
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
        $bookcomments = Model_Bookcomment::get_active_bookcomments_by_page_num($current_page, $order_by, $direction);
        $num_of_bookcomments = Model_Bookcomment::get_num_of_active_bookcomments();
        $num_of_pages = ceil($num_of_bookcomments / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
        View::set_action_var('bookcomments', $bookcomments);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
