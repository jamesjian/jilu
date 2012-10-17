<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Chapter as Model_Chapter;
use \App\Model\Chaptercategory as Model_Chaptercategory;

/**
 * homepage: /=>/front/chapter/latest/page/1
 * latest: /front/chapter/latest/page/3
 * most popular:/front/chapter/most_popular/page/3
 * chapter under category: /front/chaptercategory/retrieve/$category_id_3/category_name.php
 * one: /front/chapter/content/$id/$chapter_url
 * keyword: /front/chapter/keyword/$keyword_3
 */
class Chapter extends Front {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/chapter/';
        parent::init();
    }

    /*     * one chapter
     * /front/chapter/content/id
     */

    public function content() {
        $chapter_id = $this->params[0];
        $chapter = Model_Chapter::get_one($chapter_id);
        //\Zx\Test\Test::object_log('$chapter', $chapter, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($chapter) {
            Transaction_Html::set_title($chapter['title']);
            Transaction_Html::set_keyword($chapter['keyword'] . ',' . $chapter['keyword_en']);
            Transaction_Html::set_description($chapter['title']);
            Model_Chapter::increase_rank($chapter_id);
            $book = Model_Book::get_one($chapter['book_id']);
            View::set_view_file($this->view_path . 'one_chapter.php');
            View::set_action_var('chapter', $chapter);
            View::set_action_var('previous_chapter', $previous_chapter);
            View::set_action_var('next_chapter', $next_chapter);
            View::set_action_var('book', $book);
        } else {
            //if no chapter, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/chapter/keyword/$keyword/page/3, 3 is page number
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
            $chapters = Model_Chapter::get_active_chapters_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$chapters', $chapters, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_chapters = Model_Chapter::get_num_of_active_chapters_by_keyword($keyword);
            $num_of_pages = ceil($num_of_chapters / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            View::set_action_var('keyword', $keyword);
            View::set_action_var('chapters', $chapters);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve chapters under a category
      front/chapter/category/auzhoubaoxian/page/3, 5 is cat id, 3 is page number
      $params[0] = auzhoubaoxian, $params[1] = 'page', $params[2] = 3;
     */
    public function category() {
        $cat_title = (isset($this->params[0])) ? $this->params[0] : '';
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($cat_title != '' && $cat = Model_Chaptercategory::exist_cat_title($cat_title)) {

            //$cat = Model_Chaptercategory::get_one($cat_id);
            Transaction_Html::set_title($cat['title']);
            Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
            Transaction_Html::set_description($cat['title']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $chapters = Model_Chapter::get_active_chapters_by_cat_id_and_page_num($cat['id'], $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$chapters', $chapters, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_chapters = Model_Chapter::get_num_of_active_chapters_by_cat_id($cat['id']);
            $num_of_pages = ceil($num_of_chapters / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
            View::set_action_var('cat', $cat);
            View::set_action_var('chapters', $chapters);
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
      chapter/latest/3, 3 is page number, if missing, 1 is default page number
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
        $chapters = Model_Chapter::get_active_chapters_by_page_num($current_page, $order_by, $direction);
        $num_of_chapters = Model_Chapter::get_num_of_active_chapters();
        $num_of_pages = ceil($num_of_chapters / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_latest.php');
        View::set_action_var('chapters', $chapters);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      chapter/hottest/3, 3 is page number, if missing, 1 is default page number
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
        $chapters = Model_Chapter::get_active_chapters_by_page_num($current_page, $order_by, $direction);
        $num_of_chapters = Model_Chapter::get_num_of_active_chapters();
        $num_of_pages = ceil($num_of_chapters / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
        View::set_action_var('chapters', $chapters);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
