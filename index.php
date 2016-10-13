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
dd($stu->select('id,stu_name')->where('id<5')->get());
//dd($arr);
//$arr=$stu->getFields();
//dd($arr);
//extract($arr);
//dd(get_defined_vars());

//dd($stu->all());
//$stu->update();
//$stu->one(2);
//$stu->stu_name="赵敏";
//$stu->save();
//$stu->one(3);
//echo $stu->stu_name;
//$arr=['stu_id'=>'201656','stu_name'=>'苗人凤','class_id'=>'03'];
//$stu->add($arr)->set();
$stu->delete([25,26]);



