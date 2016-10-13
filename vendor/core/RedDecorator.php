<?php
/**
 * 文件名：RedDecorator.php
 * 文件说明:
 * 时间: 2016/10/13.15:34
 */

namespace vendor\core;


class RedDecorator implements Decorator
{
    function before()
    {
        echo "<div style='width:500px;height:300px;background-color: cornflowerblue'>";
    }

    function after()
    {
        echo "</div>";
    }

}