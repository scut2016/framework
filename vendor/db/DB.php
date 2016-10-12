<?php
/**
 * 文件名：DB.php
 * 文件说明:
 * 时间: 2016/10/12.9:41
 */

namespace vendor\db;
interface DB
{
    function connect($configs=array());
    function close();
    function query($sql);
}