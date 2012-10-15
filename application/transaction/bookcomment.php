<?php

namespace App\Transaction;

use \App\Model\Bookcomment as Model_Bookcomment;
use \Zx\Message\Message;

class Bookcomment {

    public static function create_bookcomment($arr=array())
    {
        if (count($arr)>0 && isset($arr['title'])) {
			if (!isset($arr['rank'])) $arr['rank'] = 0; //initialize
            if (Model_Bookcomment::create($arr)) {
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
    
    public static function update_bookcomment($id, $arr)
    {
		      //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
	
        if (count($arr)>0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Bookcomment::update($id, $arr)) {
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
    public static function delete_bookcomment($id)
    {
        if (Model_Bookcomment::delete($id)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
    }

    
}