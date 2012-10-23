<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
create table chapter (id int(11) auto_increment primary key, 
book_id int(11) default 0,
name varchar(255) default '', keyword varchar(255) default '', 
abstract text,image varchar(255) default '',
date_created datetime, 
status tinyint(1) default 1 comment '0: disable by chapter, 1: enable by chapter and admin (default), 2: disable by admin',
comment_status tinyint(1) default 0 comment '1. enable comment, 0. disable comment'
)engine=innodb character set=utf8;
 */

class Chapter {

    public static $fields = array('id', 'book_id', 'name', 'keyword',
        'abstract', 'image', 'date_created', 'status', 'comment_status');
    public static $table = 'chapter';

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
            $sql = "SELECT c.*, b.name as book_name
            FROM chapter c
            LEFT JOIN book b ON c.book_id=b.id
            WHERE c.id=:id
        ";
            $params = array(':id' => $id);
            return Mysql::select_one($sql, $params);
    }

    /**
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT c.*, b.name as book_name
            FROM chapter c
            LEFT JOIN book b ON c.book_id=b.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'name', $direction = 'DESC') {
        $sql = "SELECT c.*, b.name as book_name
            FROM chapter c
            LEFT JOIN book b ON c.book_id=b.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num FROM" . self::$table . "WHERE $where";
        $result = Mysql::select_one($sql);
        if ($result) {
            return $result['num'];
        } else {
            return false;
        }
    }

    public static function create($arr) {
        $insert_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        return Mysql::insert($sql, $params);
    }

    public static function update($id, $arr) {
        $update_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' . self::$table . ' SET ' . $update_str . ' WHERE id=:id';
        $params[':id'] = $id;
        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
            $sql = "Delete FROM " . self::$table ." WHERE id=:id";
            $params = array(':id' => $id);
            return Mysql::exec($sql, $params);
    }

}