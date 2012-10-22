<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
#character is the main role in the book, each book has one character, it might be different from the author
create table book (
id int(11) auto_increment primary key, 
author_id int(11) default 0,
title varchar(255) default '', keywords varchar(255) default '', 
abstract text, 
image varchar(255) default '',
character_name varchar(100) default '',
character_birthday char(8) comment 'can be only year like '19690000',
character_region_id int(11), keywords varchar(255) default '',
character_relationship varchar(50) comment 'relationship between author and character',
date_created datetime, 
status tinyint(1) default 1 comment '0: disable by author, 1: enable by author and admin (default), 2: disable by admin',
comment_status tinyint(1) default 0 comment '1. enable comment, 0. disable comment'
)engine=innodb character set=utf8;
 */

class Book {

    public static $fields = array('id', 'author_id', 'title', 'abstract',
        'image', 'character_name', 'character_birthday', 'character_region_id',
        'character_relationship', 'date_created', 'status', 'comment_status',
        
        );
    public static $table = 'book';

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
            $sql = "SELECT b.*, u.name as author_name, b.name as character_region_name
            FROM book b
            LEFT JOIN user u ON b.author_id=u.id
            LEFT JOIN region r ON b.character_region_id=r.id
            WHERE a.id=:id
        ";
            $params = array(':id' => $id);
            return Mysql::select_one($sql, $params);
    }

    /**
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT b.*, u.name as author_name, b.name as character_region_name
            FROM book b
            LEFT JOIN user u ON b.author_id=u.id
            LEFT JOIN region r ON b.character_region_id=r.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'name', $direction = 'DESC') {
        $sql = "SELECT b.*, u.name as author_name, b.name as character_region_name
            FROM book b
            LEFT JOIN user u ON b.author_id=u.id
            LEFT JOIN region r ON b.character_region_id=r.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num
            FROM book 
            WHERE $where
        ";
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
            $sql = 'DELETE FROM' . self::$table . ' WHERE id=:id';
            $params = array(':id' => $id);
            return Mysql::exec($sql, $params);
    }

}