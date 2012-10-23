<?php

namespace App\Module\Admin\Controller;

use \App\Model\Section as Model_Section;
use \App\Transaction\Section as Transaction_Section;
use \Zx\View\View;
use \Zx\Test\Test;

class Section extends Admin {

    public $list_page = '';
    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/section/';
        $this->list_page =  ADMIN_HTML_ROOT . 'section/retrieve/1/name/ASC/';
        \App\Transaction\Session::set_ck_upload_path('section');
        parent::init();
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $comment_status = isset($_POST['comment_status']) ? intval($_POST['comment_status']) : 1;

            if ($name <> '') {
                $arr = array('name' => $name, 
                    'content' => $content, 
                    'status'=>$status,
                    'comment_status' => $comment_status);
                if (Transaction_Section::create_section($arr)) {
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
        Transaction_Section::delete_section($id);
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
                if (isset($_POST['name_en']))
                    $arr['name_en'] = trim($_POST['name_en']);                
                if (isset($_POST['content']))
                    $arr['content'] = trim($_POST['content']);
                if (isset($_POST['comment_status']))
                    $arr['comment_status'] = intval($_POST['comment_status']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Section::update_section($id, $arr)) {
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
            $section = Model_Section::get_one($id);
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('section', $section);
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
        \App\Transaction\Session::set_admin_current_l1_menu('Section');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3]: '';
        if ($search != '') {
            $where = " s.name LIKE '%$search%' OR c.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $section_list = Model_Section::get_sections_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Section::get_num_of_sections($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('section_list', $section_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('section_list', $section_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    public function retrieve_by_chapter_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Section');
        $chapter_id = isset($this->params[0]) ? intval($this->params[0]) :0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4]: '';
        if ($search != '') {
            $where = " c.name LIKE '%$search%' OR b.name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $section_list = Model_Section::get_sections_by_chapter_id_and_page_num($chapter_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Section::get_num_of_sections($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ARTICLES_IN_CAT_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_chapter_id.php');
        View::set_action_var('chapter_id', $chapter_id);
        View::set_action_var('section_list', $section_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }       
}
