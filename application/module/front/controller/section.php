<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Section as Model_Section;
use \App\Model\Sectioncategory as Model_Sectioncategory;

/**
 * homepage: /=>/front/section/latest/page/1
 * latest: /front/section/latest/page/3
 * most popular:/front/section/most_popular/page/3
 * section under category: /front/sectioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/section/content/$id/$section_url
 * keyword: /front/section/keyword/$keyword_3
 */
class Section extends Front {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/section/';
        parent::init();
    }

    /*     * one section
     * /front/section/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $section_url = $this->params[0]; //it's url rather than an id

        $section = Model_Section::get_one_by_url($section_url);
        //\Zx\Test\Test::object_log('$section', $section, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($section) {
            $section_id = $section['id'];
            Transaction_Html::set_title($section['title']);
            Transaction_Html::set_keyword($section['keyword'] . ',' . $section['keyword_en']);
            Transaction_Html::set_description($section['title']);
            Model_Section::increase_rank($section_id);

            View::set_view_file($this->view_path . 'one_section.php');
            $relate_sections = Model_Section::get_10_active_related_sections($section_id);
            View::set_action_var('section', $section);
            View::set_action_var('related_sections', $relate_sections);
        } else {
            //if no section, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/section/keyword/$keyword/page/3, 3 is page number
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
            $sections = Model_Section::get_active_sections_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$sections', $sections, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_sections = Model_Section::get_num_of_active_sections_by_keyword($keyword);
            $num_of_pages = ceil($num_of_sections / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            View::set_action_var('keyword', $keyword);
            View::set_action_var('sections', $sections);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve sections under a category
      front/section/category/auzhoubaoxian/page/3, 5 is cat id, 3 is page number
      $params[0] = auzhoubaoxian, $params[1] = 'page', $params[2] = 3;
     */
    public function category() {
        $cat_title = (isset($this->params[0])) ? $this->params[0] : '';
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($cat_title != '' && $cat = Model_Sectioncategory::exist_cat_title($cat_title)) {

            //$cat = Model_Sectioncategory::get_one($cat_id);
            Transaction_Html::set_title($cat['title']);
            Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
            Transaction_Html::set_description($cat['title']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $sections = Model_Section::get_active_sections_by_cat_id_and_page_num($cat['id'], $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$sections', $sections, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_sections = Model_Section::get_num_of_active_sections_by_cat_id($cat['id']);
            $num_of_pages = ceil($num_of_sections / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
            View::set_action_var('cat', $cat);
            View::set_action_var('sections', $sections);
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
      section/latest/3, 3 is page number, if missing, 1 is default page number
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
        $sections = Model_Section::get_active_sections_by_page_num($current_page, $order_by, $direction);
        $num_of_sections = Model_Section::get_num_of_active_sections();
        $num_of_pages = ceil($num_of_sections / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_latest.php');
        View::set_action_var('sections', $sections);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      section/hottest/3, 3 is page number, if missing, 1 is default page number
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
        $sections = Model_Section::get_active_sections_by_page_num($current_page, $order_by, $direction);
        $num_of_sections = Model_Section::get_num_of_active_sections();
        $num_of_pages = ceil($num_of_sections / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
        View::set_action_var('sections', $sections);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
