<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
create table author (id int(11) auto_increment primary key,
name varchar(100) default '', password varchar(32) default '',
region_id int(11), keyword varchar(255) default '', image varchar(255) default '',
date_created datetime, 
status tinyint(1) default 1 comment '0:register, 1: enabled, 2: disabled by admin'
)engine=innodb character set=utf8;
 */

class Author {

    public static $fields = array('id', 'name', 'password', 'region_id',
        'keyword', 'image', 'date_created', 'status');
    public static $table = 'author';

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
            $sql = "SELECT a.*, r.name as region_name
            FROM author a
            LEFT JOIN region r ON a.region_id=r.id
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
        $sql = "SELECT a.*, r.name as region_name
            FROM author a
            LEFT JOIN region r ON a.region_id=r.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'name', $direction = 'DESC') {
        $sql = "SELECT a.*, r.name as region_name
            FROM author a
            LEFT JOIN region r ON a.region_id=r.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num
            FROM author 
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
            $sql = "DELETE FROM author WHERE id=:id";
            $params = array(':id' => $id);
            return Mysql::exec($sql, $params);
    }

}