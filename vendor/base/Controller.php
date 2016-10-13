<?php
/**
 * 文件名：Controller.php
 * 文件说明:
 * 时间: 2016/10/12.9:33
 */

namespace vendor\base;
use vendor\core\Factory;
use vendor\core\Register;

class Controller
{
    protected $model=null;
    function __construct()
    {
        $modelName=$this->getModelName();
        if(!Register::get($modelName))
            $model=Factory::createModel($modelName);
        else
            $model=Register::get($modelName);
     return  $this->model=$model;
    }

    private function getModelName()
    {
        $class=get_class($this);
        $model=str_replace('controller','model',$class);
        $model='\\'.str_replace('Controller','Model',$model);
        return $model;
    }
    
}