<?php
/**
 * 文件名：StudentController.php
 * 文件说明:
 * 时间: 2016/10/13.14:12
 */

namespace backend\controllers;
use vendor\base\Controller;

class StudentController extends Controller
{

    function index()
    {
//        $arr=$this->model->all()->current();
//        $this->display($arr);

    }
    function rulers()
    {
      return  [
                ['stu_id', 'required'],
                ['stu_id', 'unique',  'message' => 'stu_id已经存在'],
                ['stu_name', 'string', 'min' => 2, 'max' => 4,'message' => 'stu_name必须是合法的字符串，长度为2-4'],
                ['stu_name', 'required',],
      
      ];

    }

    function __destruct()
    {


    }


}