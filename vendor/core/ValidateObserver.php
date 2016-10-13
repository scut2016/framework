<?php
/**
 * 文件名：ValidateObserver.php
 * 文件说明:
 * 时间: 2016/10/13.16:06
 */

namespace vendor\core;


class ValidateObserver implements Observer
{
    function update($rulers = array())
    {
        if($rulers['username']=='admin')
            echo "欢迎管理员admin登录";
        else
            echo '欢迎普通用户'.$rulers['username'].'登录';
    }

}