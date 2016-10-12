<?php
/**
 * 文件名：index.php
 */
header("Content-type:text/html;charset=utf-8");
define('APP',__DIR__);
include "vendor/include.php";
$db=\vendor\core\Factory::getDb();
dd($db);