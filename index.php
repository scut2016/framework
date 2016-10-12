<?php
/**
 * 文件名：index.php
 */
use vendor\core\Register;
header("Content-type:text/html;charset=utf-8");
define('APP',__DIR__);
include "vendor/include.php";

$model=new \vendor\base\Model();
dd($model->all());