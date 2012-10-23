<?php

namespace App\Model;

use \App\Model\Base\Character as Base_Character;
use \Zx\Model\Mysql;

/**
 * 
 */
class Character extends Base_Character{
    public static function get_characters_by_page_num($page_num = 1, $order_by = 'c.name', $direction = 'ASC') {
        $where = ' 1 ';
        $offset = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $offset, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }
}


