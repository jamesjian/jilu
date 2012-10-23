<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
#character is the main role in the book, each book has one character, it might be different from the author
create table character (
id int(11) auto_increment primary key, 
image varchar(255) default '',
name varchar(100) default '',
birthday char(8) comment 'can be only year like '19690000',
region_id int(11), keywords varchar(255) default '',
date_created datetime, 
status tinyint(1) default 1 comment ''
)engine=innodb character set=utf8;
 */

class Character {

    public static $fields = array('id', 'image', 'name', 'birthday', 'region_id',
        'date_created', 'status', 
        );
    public static $table = 'character';

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
            $sql = "SELECT c.*, r.name as region_name
            FROM character c
            LEFT JOIN region r ON c.region_id=r.id
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
        $sql = "SELECT c.*, r.name as region_name
            FROM character c
            LEFT JOIN region r ON c.region_id=r.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'name', $direction = 'DESC') {
        $sql = "SELECT c.*, r.name as region_name
            FROM character c
            LEFT JOIN region r ON c.region_id=r.id
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
            $sql = 'DELETE FROM' . self::$table . ' WHERE id=:id';
            $params = array(':id' => $id);
            return Mysql::exec($sql, $params);
    }

}