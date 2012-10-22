<?php

namespace App\Module\Admin\Controller;

use \App\Model\Book as Model_Book;
use \App\Transaction\Book as Transaction_Book;
use \Zx\View\View;
use \Zx\Test\Test;

class Book extends Admin {

    public $list_page = '';

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/book/';
        $this->list_page = ADMIN_HTML_ROOT . 'book/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('book');
        parent::init();
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
            $keyword_en = isset($_POST['keyword_en']) ? trim($_POST['keyword_en']) : '';
            $abstract = isset($_POST['abstract']) ? trim($_POST['abstract']) : '';
            $url = isset($_POST['url']) ? trim($_POST['url']) : '';
            $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 1;
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title,
                    'title_en' => $title_en,
                    'content' => $content,
                    'keyword' => $keyword,
                    'keyword_en' => $keyword_en,
                    'abstract' => $abstract,
                    'url' => $url,
                    'rank' => $rank,
                    'status' => $status,
                    'cat_id' => $cat_id);
                if (Transaction_Book::create_book($arr)) {
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
        Transaction_Book::delete_book($id);
        header('Location: ' . $this->list_page);
    }

    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            \Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['title']))
                    $arr['title'] = trim($_POST['title']);
                if (isset($_POST['title_en']))
                    $arr['title_en'] = trim($_POST['title_en']);
                if (isset($_POST['content']))
                    $arr['content'] = trim($_POST['content']);
                if (isset($_POST['keyword']))
                    $arr['keyword'] = trim($_POST['keyword']);
                if (isset($_POST['keyword_en']))
                    $arr['keyword_en'] = trim($_POST['keyword_en']);
                if (isset($_POST['abstract']))
                    $arr['abstract'] = trim($_POST['abstract']);
                if (isset($_POST['url']))
                    $arr['url'] = trim($_POST['url']);
                if (isset($_POST['rank']))
                    $arr['rank'] = intval($_POST['rank']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Book::update_book($id, $arr)) {
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
            $book = Model_Book::get_one($id);
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
        \App\Transaction\Session::set_admin_current_l1_menu('Book');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " b.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $book_list = Model_Book::get_books_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Book::get_num_of_books($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('book_list', $book_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('book_list', $book_list);
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
