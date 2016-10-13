<?php
$config= [
    'master'=>[
                'type'      =>  'mysql',
                'hostname'  =>  'localhost',
                'username'  =>  'root',
                'password'  =>  'liufeng',
                'dbName'    =>  'train',
                'charset'   =>  'utf8',
                'params'    =>array(),
              ],
    'slave'=>[
                'ttc'=>
                [
                    'type'      =>  'mysql',
                    'hostname'  =>  '192.168.1.52',
                    'username'  =>  'guest',
                    'password'  =>  '',
                    'dbName'    =>  'train',
                    'charset'   =>  'utf8',
                    'params'    =>array(),
                ],
             'tzy'=>
                [
                    'type'      =>  'mysql',
                    'hostname'  =>  '192.168.1.52',
                    'username'  =>  'guest',
                    'password'  =>  '',
                    'dbName'    =>  'train',
                    'charset'   =>  'utf8',
                    'params'    =>array(),
                ],
            ],
];
return $config;