<?php
/**
 * 文件名：ActiveRecord.php
 * 数据映射类
 * 日期: 2016/10/12 23:05
 */

namespace vendor\core;

class ActiveRecord
{
    private $data=array();
    function __construct($table)
    {
        $sql="select * from $table";

    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
}