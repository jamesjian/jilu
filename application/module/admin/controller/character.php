<?php

namespace App\Module\Admin\Controller;

use \App\Model\Character as Model_Character;
use \App\Transaction\Character as Transaction_Character;
use \Zx\View\View;
use \Zx\Test\Test;

class Character extends Admin {

    public $list_page = '';

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/character/';
        $this->list_page = ADMIN_HTML_ROOT . 'character/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('character');
        parent::init();
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : '';
            $region_id = isset($_POST['region_id']) ? intval($_POST['region_id']) : 0;
            //$status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $status = 1;
            if ($title <> '') {
                $arr = array('name' => $name,
                    'birthday' => $birthday,
                    'region_id' => $region_id,
                    'status' => $status,
                    );
                if (Transaction_Character::create_character($arr)) {
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
        Transaction_Character::delete_character($id);
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
                if (isset($_POST['birthday']))
                    $arr['birthday'] = trim($_POST['birthday']);
                if (isset($_POST['region_id']))
                    $arr['region_id'] = trim($_POST['region_id']);
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
            $book = Model_Character::get_one($id);
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('book', $book);
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
        \App\Transaction\Session::set_admin_current_l1_menu('Character');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " c.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $character_list = Model_Character::get_characters_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Character::get_num_of_books($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('book_list', $book_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('character_list', $character_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    public function retrieve_by_author_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Book');
        $author_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " b.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $book_list = Model_Book::get_books_by_author_id_and_page_num($author_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Book::get_num_of_books($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_author_id.php');
        View::set_action_var('author_id', $author_id);
        View::set_action_var('book_list', $book_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    public function retrieve_by_province_id()
    {
        
    }
    public function retrieve_by_city_id()
    {
        
    }
    public function retrieve_by_district_id()
    {
        
    }

}
