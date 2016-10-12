<?php
/**
 * 文件名：Proxy.php
 * 文件说明:
 * 时间: 2016/10/12.14:37
 */

namespace vendor\core;

class Proxy
{
   static function  writeDb()
    {
        if(!Register::get('master'))
             $db=Factory::getDb();
        else
            $db=Register::get('master');
        return $db;
    }
    static function readDb()
    {
        if(!Register::get('slave'))
            $db=Factory::getDb('slave');
        else
            $db=Register::get('slave');
        return $db;
    }
}