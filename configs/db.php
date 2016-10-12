<?php
$config= [
    'master'=>[
                'type'      =>  'mysql',
                'hostname'  =>  'localhost',
                'username'  =>  'root',
                'password'  =>  'root',
                'dbName'    =>  'train',
                'charset'   =>  'utf8',
                'params'    =>array(),
              ],
    'slave'=>[
                'ttc'=>
                [
                    'type'      =>  'mysql',
                    'hostname'  =>  '192.168.0.106',
                    'username'  =>  'root',
                    'password'  =>  'root',
                    'dbName'    =>  'train',
                    'charset'   =>  'utf8',
                    'params'    =>array(),
                ],
             'wxn'=>
                [
                    'type'      =>  'mysql',
                    'hostname'  =>  '192.168.0.106',
                    'username'  =>  'root',
                    'password'  =>  'root',
                    'dbName'    =>  'train',
                    'charset'   =>  'utf8',
                    'params'    =>array(),
                ],
            ],
];
return $config;