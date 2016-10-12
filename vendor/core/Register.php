<?php

namespace vendor\core;

class Register
{
    protected static $_object=[];
    static function set($alias,$object)
    {
        self::$_object[$alias]=$object;
    }
    function _unset($alias)
    {
        unset(self::$_object[$alias]);
    }
    static function get($alias)
    {
        if(isset(self::$_object[$alias]))
            return self::$_object[$alias];
        else
            return null;
    }
}