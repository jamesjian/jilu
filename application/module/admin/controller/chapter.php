<?php

namespace App\Module\Admin\Controller;

use \App\Model\Chapter as Model_Chapter;
use \App\Transaction\Chapter as Transaction_Chapter;
use \Zx\View\View;
use \Zx\Test\Test;

class Chapter extends Admin {

    public $list_page = '';
    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/chapter/';
        $this->list_page =  ADMIN_HTML_ROOT . 'chapter/retrieve/1/name/ASC/';
        \App\Transaction\Session::set_ck_upload_path('chapter');
        parent::init();
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $abstract = isset($_POST['abstract']) ? trim($_POST['abstract']) : '';
            $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 1;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($name <> '') {
                $arr = array('name' => $name, 
                    'abstract' => $abstract, 
                    'status'=>$status,
                    'book_id' => $book_id);
                if (Transaction_Chapter::create_chapter($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
        }
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Chapter::delete_chapter($id);
        header('Location: ' . $this->list_page);
    }

    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            //\Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['name']))
                    $arr['name'] = trim($_POST['name']);
                if (isset($_POST['abstract']))
                    $arr['abstract'] = trim($_POST['abstract']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Chapter::update_chapter($id, $arr)) {
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
            $chapter = Model_Chapter::get_one($id);
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('chapter', $chapter);
        }
    }
    public function search() {
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = $this->list_page . trim($_POST['search']);
        } else {
            $link = $this->list_page;
        }
        header('Location: ' . $link);
    }
    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_admin_current_l1_menu('Chapter');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3]: '';
        if ($search != '') {
            $where = " c.name LIKE '%$search%' OR b.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $chapter_list = Model_Chapter::get_chapters_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Chapter::get_num_of_chapters($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('chapter_list', $chapter_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('chapter_list', $chapter_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    public function retrieve_by_book_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Chapter');
        $book_id = isset($this->params[0]) ? intval($this->params[0]) :0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4]: '';
        if ($search != '') {
            $where = " c.name LIKE '%$search%' OR b.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $chapter_list = Model_Chapter::get_chapters_by_book_id_and_page_num($book_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Chapter::get_num_of_chapters($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ARTICLES_IN_CAT_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_book_id.php');
        View::set_action_var('book_id', $book_id);
        View::set_action_var('chapter_list', $chapter_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }    
}
