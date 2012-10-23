<?php

namespace App\Model;

use \App\Model\Base\Chapter as Base_Chapter;
use \Zx\Model\Mysql;

class Chapter extends Base_Chapter{
    public static function get_chapters_by_page_num($page_num = 1, $order_by = 's.name', $direction = 'ASC') {
        $where = ' 1 ';
        $offset = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $offset, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }
    public static function get_chapters_by_book_id_and_page_num($book_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' c.book_id=' . $book_id;
        $offset = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $offset, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }    

}