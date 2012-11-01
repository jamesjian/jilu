<?php

namespace App\Model\Base;

/**
  CREATE TABLE IF NOT EXISTS `cache` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` text COMMENT 'serialized value',
  `date_created1` int(11) DEFAULT NULL COMMENT 'unix timestamp',
  `date_created2` datetime NOT NULL,
  `expire` int(11) DEFAULT NULL COMMENT 'unix timestamp, if 0, never expires',
  PRIMARY KEY (`cache_name`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * time use unix timestamp(integer), not datetime
 */
//in case 'empty' or 'false' is the value of the cache, use NO_CACHE_DATA as the value
//when there is no value for a particular cache id


use \Zx\Model\Mysql;

class Cache {

    public static $fields = array('cache_name', 'cache_value',
        'date_created1', 'date_created2', 'expire');
    public static $table = 'cache';

    //private static $last_insert_id = 0;

    /**
     *
     * @param <string> $cache_id
     * @return <string or boolean>
     * if data exists and is not expired, return it,
     * else if expired, delete it and return false
     * else return false
     */
    public static function get_one($cache_name) {
        $sql = "SELECT *
            FROM cache 
            WHERE cache_name = :cache_name
        ";
        $params = array(':cache_name' => $cache_name);
        $cache = Mysql::select_one($sql, $params);


        if ($cache) {
            if (intval($cache['expire']) == 0 || ($cache['date_created1'] + $cache['expire']) <= time()) {
                //if it's not expired, return it
                return $cache;
            } else {
                //if it's expired, delete it
                self::delete($cache_name);
                return NO_CACHE_DATA;
            }
        } else {
            //if not found
            return NO_CACHE_DATA;
        }
    }

    /**
     * @param <string> $cache_name
     * @return <boolean> return true
     */
    public static function delete($cache_name) {
        $sql = "Delete FROM " . self::$table . " WHERE id=:id";
        $params = array(':cache_name' => $cache_name);
        return Mysql::exec($sql, $params);
    }

    /**
     * delete all cache records
     * @return <boolean> return true
     */
    public static function delete_all_cache() {
        $sql = "Delete FROM " . self::$table . " WHERE 1";
        return Mysql::exec($sql);
    }

    /**
     * @param <string> $cache_name
     * @return <boolean> return true or false
     */
    public static function exist_cache($cache_name) {
        $cache = self::get_one($cache_name);
        if ($cache != NO_CACHE_DATA)
            return true;
        else
            return false;
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

    public static function update($cache_name, $arr) {
        $update_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }

        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' . self::$table . ' SET ' . $update_str . ' WHERE cache_name=:cache_name';
        $params[':cache_name'] = $cache_name;
        return Mysql::exec($sql, $params);
    }

}
