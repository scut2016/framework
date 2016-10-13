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
       dd($this->model->all()->current());
    }
    
    
}