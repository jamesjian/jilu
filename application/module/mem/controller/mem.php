<?php

namespace App\Module\Mem\Controller;

/*
 * this is the base class of mem classes
 * only for logged user, if a user has not logged in, stay in front module
 * some front module still check user is logged in or not, such as comment page
 */
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Transaction\User as Transaction_User;
use \App\Model\User as Model_User;

class Mem {

    public $template_path;
    public $view_path = '';
    public $params = array();
    public $user = null;

    public function init() {
        $this->params = Route::get_params();
        $this->template_path = APPLICATION_PATH . 'module/mem/view/templates/';
        View::set_template_file($this->template_path . 'template.php');
        View::set_template_var('title', 'this is mem title');
        View::set_template_var('keyword', 'this is mem keyword');
        $action = Route::get_action();
        if ($action == 'login' || $action == 'logout') {
            
        } else {
            if (Transaction_User::user_has_loggedin()) {
                $this->user = Model_User::get_one(Transaction_User::get_user_id());
            } else {
                header('Location: ' . HTML_ROOT . 'front/user/login');  
            }
        }
    }

}