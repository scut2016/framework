<?php
/**
 * 文件名：Factory.php
 * 文件说明:
 * 时间: 2016/10/12.9:43
 */

namespace vendor\core;
use vendor\db\driver\MySQLi;
use vendor\db\driver\PDO;

class Factory
{
    static function getDb($type='master')
    {
        $configs=new \vendor\core\Config(APP.'/configs');
        if($type=='master')
        {
            $config=$configs['db'][$type];
        }
        else
        {
            $slaves=$configs['db']['slave'];
            $config=$slaves[array_rand($slaves)];
        }
        $connType=$config['type'];
        $db=null;
        switch ($connType)
        {
            case 'mysql':
                MySQLi::reset();
                $db=MySQLi::getInstance($config);
                break;
            case 'pdo':
                $db=PDO::getInstance($config);
                break;
        }
        Register::set($type,$db);
        return $db;
    }

}