<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
 * id=0 is the root of all regions
 * 
 * name "直辖市" is a province
 * it's parent of beijing, shanghai, tianjin, chongqing
 * 
  create table region(id int(11) auto_increment primary key,
name varchar(100), 
type tinyint(1) comment '1: province, 2.municipality , 3. city, 4.district 5.county ',
parent_id id(11) comment 'city is the parent of districts or counties',
is_capital tinyint(1) comment 'guangzhou is a capital, 1: capital, 0: not a capital',
display_order mediumint(5) comment 'smaller, topper',
status tinyint(1) 
)) engine=innodb character set=utf8;
 */

class Region {

    public static $fields = array('id', 'name', 'type', 'parent_id',
        'is_capital', 'display_order', 'status');
    public static $table = 'region';

    /**
     *
     * id=0 is the root of all regions
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        if ($id > 0) {
            $sql = "SELECT r.*, p.name as parent_name
            FROM region r
            LEFT JOIN region p ON r.parent_id=p.id
            WHERE r.id=:id
        ";
            $params = array(':id' => $id);
            return Mysql::select_one($sql, $params);
        } else {
            return false;
        }
    }

    /**
     * "where id=0" is invalid
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT r.*, p.name as parent_name
            FROM region r
            LEFT JOIN region p ON r.parent_id=p.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'name', $direction = 'DESC') {
        $sql = "SELECT r.*, p.name as parent_name
            FROM region r
            LEFT JOIN region p ON r.parent_id=p.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        //\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num
            FROM region 
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
        if ($id > 0) {
            $sql = 'DELETE FROM' . self::$table . ' WHERE id=:id';
            $params = array(':id' => $id);
            return Mysql::exec($sql, $params);
        } else {
            return false;
        }
    }

}