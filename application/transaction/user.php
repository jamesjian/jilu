<?php

namespace App\Transaction;

use \App\Model\User as Model_User;
use \Zx\Message\Message;

class User {

    public static function create_user($arr=array())
    {
        if (count($arr)>0 && isset($arr['title'])) {
			if (!isset($arr['rank'])) $arr['rank'] = 0; //initialize
            if (Model_User::create($arr)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('wrong info');
            return false;
        }
    }
    
    public static function update_user($id, $arr)
    {
		      //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
	
        if (count($arr)>0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_User::update($id, $arr)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('wrong info');
            return false;
        }        
    }
    public static function delete_user($id)
    {
        if (Model_User::delete($id)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
    }

    
}