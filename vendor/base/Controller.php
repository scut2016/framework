<?php
/**
 * 文件名：Controller.php
 * 文件说明:
 * 时间: 2016/10/12.9:33
 */

namespace vendor\base;
use vendor\core\Factory;
use vendor\core\Observer;
use vendor\core\RedDecorator;
use vendor\core\Register;
use vendor\core\Decorator;
use vendor\core\ValidateObserver;

class Controller
{
    protected $model=null;
    protected $rulers=[];
    protected $decorators=[];
    protected $observers=[];
    
    function addDecorator(Decorator $decorator)
    {
        $this->decorators[]=$decorator;
    }
    function addObserver(Observer $observer)
    {
        $this->observers[]=$observer;
    }
    function notify()
    {
        foreach ($this->observers as $observer)
        {
            $observer->update($this->model,$this->rulers);
        }
    }
    
    function __construct()
    {
        $this->addDecorator(new RedDecorator());
        if(isset($this->rulers))
            $this->addObserver(new ValidateObserver());
        $modelName=$this->getModelName();
        if(!Register::get($modelName))
            $model=Factory::createModel($modelName);
        else
            $model=Register::get($modelName);
         $this->model=$model;
         $this->notify();
    }

    private function getModelName()
    {
        $class=get_class($this);
        $model=str_replace('controller','model',$class);
        $model='\\'.str_replace('Controller','Model',$model);
        return $model;
    }
    
    
    
}