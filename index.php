<?php
/**
 * 文件名：index.php
 */
use vendor\core\Register;
header("Content-type:text/html;charset=utf-8");
define('APP',__DIR__);
include "vendor/include.php";

//$model=new \vendor\base\Model();
//$all=$model->all();
////$all->next();
////$all->next();
////dd($all->current());
////$all->next();
////$all->next();
////$all->next();
//dd($model->one(3));
$stu=new \backend\models\StudentModel();
$arr=$stu->getFields();
//dd($arr);
//extract($arr);
//dd(get_defined_vars());

//dd($stu->all());
//$stu->update();
$stu->one(2);
$stu->stu_name="赵敏";
$stu->one(3);
echo $stu->stu_name;



