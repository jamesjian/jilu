<?php

namespace App\Model;

use \App\Model\Base\Section as Base_Section;
use \Zx\Model\Mysql;

class Section extends Base_Section{
    public static function get_sections_by_page_num($page_num = 1, $order_by = 's.name', $direction = 'ASC') {
        $where = ' 1 ';
        $offset = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $offset, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }
    public static function get_sections_by_chapter_id_and_page_num($chapter_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' s.chapter_id=' . $chapter_id;
        $offset = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $offset, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }    
    
    /**
     * check if a chapter has sections
     * @param int $chapter_id
     * @return boolean
     */
    public static function exist_section_by_chapter_id($chapter_id)
    {
        $where = ' s.chapter_id=' . $chapter_id;
        if (parent::get_one_by_where($where)) {
            return true;
        } else {
            return false;
        }
    }
}