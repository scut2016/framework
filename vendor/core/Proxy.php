<?php
/**
 * 文件名：Proxy.php
 * 文件说明:
 * 时间: 2016/10/12.14:37
 */

namespace vendor\core;

class Proxy
{
    function __construct()
    {
       
    }

    static function  writeDb()
    {
       return Register::get('master');
    }
    static function readDb()
    {
       return  Register::get('slave');
    }
}