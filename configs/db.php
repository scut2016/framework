<?php
$config= [
    'master'=>[
                'type'      =>  'mysql',
                'hostname'  =>  'localhost',
                'username'  =>  'root',
                'password'  =>  'liufeng',
                'dbName'    =>  'train',
                'charset'   =>  'utf8',
              ],
    'slave'=>[
                'obx'=>
                [
                    'type'      =>  'mysql',
                    'hostname'  =>  '192.168.1.62',
                    'username'  =>  'guest',
                    'password'  =>  '',
                    'dbName'    =>  'train',
                    'charset'   =>  'utf8',
                ],
             'ttc'=>
                [
                    'type'      =>  'mysql',
                    'hostname'  =>  '192.168.1.41',
                    'username'  =>  'guest',
                    'password'  =>  '',
                    'dbName'    =>  'train',
                    'charset'   =>  'utf8',
                ],
            ],
];
return $config;