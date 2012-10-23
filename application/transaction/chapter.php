<?php

namespace App\Transaction;

use \App\Model\Chapter as Model_Chapter;
use \Zx\Message\Message;

class Chapter {

    public static function create_chapter($arr = array()) {
        if (count($arr) > 0 && isset($arr['title'])) {
            if (Model_Chapter::create($arr)) {
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

    public static function update_chapter($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && (isset($arr['title']) || isset($arr['abstract']))) {
            if (Model_Chapter::update($id, $arr)) {
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

    public static function delete_chapter($id) {
        if (!Model_Section::exist_section_by_chapter_id($id)) {
            if (Model_Chapter::delete($id)) {
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