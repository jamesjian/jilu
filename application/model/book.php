<?php

namespace App\Model;

use \App\Model\Base\Book as Base_Book;
use \Zx\Model\Mysql;

class Book extends Base_Book{

    /**
     * check if a character exists in book table
     * @param int $character_id
     * @return boolean
     */
    public static function has_character($character_id)
    {
        $where = ' b.character_id=' . $character_id;
        if (parent::get_one_by_where($where)) {
            return true;
        } else {
            return false;
        }
    }
}