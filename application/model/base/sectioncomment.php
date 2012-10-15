<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*

creat table section_comment (
id int(11) auto_increment primary key, 
section_id int(11) default 0 comment 'even if reply to an existing comment, record section id for query',
user_id int(11), 
staff_id int(11) default 0 comment 'if a staff update an existing comment, record the staff id', 
title varchar(255) default '', 
content text,image varchar(255) default '',
parent_id int(11) default 0 comment 'if reply an existing comment, parent id is this existing comment id, else parent id is 0',
date_created datetime, status tinyint(1))engine=innodb character set=utf8;
 */

class Sectioncomment {

    public static $fields = array('id', 'section_id', 'user_id', 'staff_id',
        'title', 'content', 'parent_id', 'date_created','status');
    public static $table = 'section_comment';

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
            $sql = "SELECT sc.*, c.name as section_name, u.name as user_name, s.name as staff_name
            FROM section_comment sc
            LEFT JOIN chapter c ON sc.book_id=c.id
            LEFT JOIN user u ON sc.user_id=u.id
            LEFT JOIN staff s ON sc.staff_id=s.id
            WHERE sc.id=:id
        ";
            $params = array(':id' => $id);
            return Mysql::select_one($sql, $params);
    }

    /**
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT sc.*, c.name as section_name, u.name as user_name, s.name as staff_name
            FROM section_comment sc
            LEFT JOIN chapter c ON sc.book_id=c.id
            LEFT JOIN user u ON sc.user_id=u.id
            LEFT JOIN staff s ON sc.staff_id=s.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'name', $direction = 'DESC') {
        $sql = "SELECT sc.*, c.name as section_name, u.name as user_name, s.name as staff_name
            FROM section_comment sc
            LEFT JOIN chapter c ON sc.book_id=c.id
            LEFT JOIN user u ON sc.user_id=u.id
            LEFT JOIN staff s ON sc.staff_id=s.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = 'SELECT COUNT(id) AS num FROM ' . self::$table . " WHERE $where";
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
            $sql = 'DELETE FROM ' . self::$table . ' WHERE id=:id';
            $params = array(':id' => $id);
            return Mysql::exec($sql, $params);
    }

}