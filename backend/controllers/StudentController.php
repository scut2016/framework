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

    function __construct()
    {
        parent::__construct();

    }

    function index()
    {
        $d=$this->decorators[0];
        $d->before();
//       dd($this->model->all()->current());

        echo "Hello world!";
        $d->after();
    }
    function __destruct()
    {


    }


}