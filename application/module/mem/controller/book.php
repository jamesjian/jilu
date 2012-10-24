<?php

namespace App\Module\Mem\Controller;

use \App\Model\Article as Model_Article;
use \App\Model\Book as Model_Book;
use \Zx\View\View;
use \Zx\Test\Test;

class Book extends Mem {

    public $list_page = '';
    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/mem/view/book/';
        $this->list_page =  MEM_HTML_ROOT . 'book/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('book');
        parent::init();
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $abstract = isset($_POST['abstract']) ? trim($_POST['abstract']) : '';
            $character_name = isset($_POST['character_name']) ? trim($_POST['character_name']) : '';
            $character_birthday = isset($_POST['character_birthday']) ? trim($_POST['character_birthday']) : '';
            $character_region_id = isset($_POST['character_region_id']) ? intval($_POST['character_region_id']) : 0;
            $character_relationship = isset($_POST['character_relationship']) ? trim($_POST['character_relationship']) : '';

            if ($title <> '') {
                $arr = array('title' => $title, 
                     'abstract'=>$abstract, 
                    'character_name' => $character_name, 
                    'character_birthday'=>$character_birthday,
                    'character_region_id'=>$character_region_id,
                    'character_relationship'=>$character_relationship, 
                    );
                if (Transaction_Book::create_book($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $cats = Model_Articlecategory::get_all_cats();
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('cats', $cats);
        }
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Article::delete_article($id);
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
                if (isset($_POST['cat_id']))
                    $arr['cat_id'] = intval($_POST['cat_id']);
                if (isset($_POST['rank']))
                    $arr['rank'] = intval($_POST['rank']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Article::update_article($id, $arr)) {
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
            $article = Model_Article::get_one($id);

            $cats = Model_Articlecategory::get_cats();
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('article', $article);
            View::set_action_var('cats', $cats);
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
        \App\Transaction\Session::set_admin_current_l1_menu('Article');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3]: '';
        if ($search != '') {
            $where = " b.title LIKE '%$search%' OR bc.title LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $article_list = Model_Article::get_articles_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Article::get_num_of_articles($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('article_list', $article_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    

}
