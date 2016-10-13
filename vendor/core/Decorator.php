<?php
/**
 * 文件名：ValidateDecorator.php
 * 文件说明:
 * 时间: 2016/10/13.15:27
 */

namespace vendor\core;
interface Decorator
{
    function before();
    function after();
}