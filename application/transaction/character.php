<?php

namespace App\Transaction;

use \App\Model\Character as Model_Character;
use \Zx\Message\Message;

class Character {

    public static function create_character($arr=array())
    {
        if (count($arr)>0 && isset($arr['name'])) {
            if (Model_Character::create($arr)) {
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
    
    public static function update_character($id, $arr)
    {
		      //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
	
        if (count($arr)>0 && isset($arr['name']) ) {
            if (Model_Character::update($id, $arr)) {
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
    /**
     * need to check if book exists
     * @param type $id
     * @return boolean
     */
    public static function delete_character($id)
    {
        if (!Model_Book::has_character($id)) {
            if (Model_Character::delete($id)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('one book has this character');
        }
    }

    
}