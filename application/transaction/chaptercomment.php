<?php

namespace App\Transaction;

use \App\Model\Chaptercomment as Model_Chaptercomment;
use \Zx\Message\Message;

class Chaptercomment {

    public static function create_chaptercomment($arr=array())
    {
        if (count($arr)>0 && isset($arr['title'])) {
			if (!isset($arr['rank'])) $arr['rank'] = 0; //initialize
            if (Model_Chaptercomment::create($arr)) {
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
    
    public static function update_chaptercomment($id, $arr)
    {
		      //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
	
        if (count($arr)>0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Chaptercomment::update($id, $arr)) {
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
    public static function delete_chaptercomment($id)
    {
        if (Model_Chaptercomment::delete($id)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
    }

    
}