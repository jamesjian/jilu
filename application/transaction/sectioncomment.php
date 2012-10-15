<?php

namespace App\Transaction;

use \App\Model\Sectioncomment as Model_Article;
use \Zx\Message\Message;

class Article {

    public static function create_sectioncomment($arr=array())
    {
        if (count($arr)>0 && isset($arr['title'])) {
			if (!isset($arr['rank'])) $arr['rank'] = 0; //initialize
            if (Model_Article::create($arr)) {
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
    
    public static function update_sectioncomment($id, $arr)
    {
		      //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
	
        if (count($arr)>0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Article::update($id, $arr)) {
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
    public static function delete_sectioncomment($id)
    {
        if (Model_Article::delete($id)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
    }

    
}