<?php
/**
 * 文件名：Observer.php
 * 文件说明:
 * 时间: 2016/10/13.16:06
 */

namespace vendor\core;

interface Observer
{
    function update($rulers=array());
}