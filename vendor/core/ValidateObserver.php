<?php
/**
 * 文件名：ValidateObserver.php
 * 文件说明:
 * 时间: 2016/10/13.16:06
 */

namespace vendor\core;


class ValidateObserver implements Observer
{
    protected $rulers=['unique','required'];
    protected $type=['string','int','double'];
    public $errors=[];
    protected $model=null;
    function update($model,$rulers = array())
    {
        $this->model=$model;
        $result=$this->getRuler($rulers);
        dd($result);
        foreach ($result as $key=>$value)
        {
            foreach ($value as $k=>$v)
            {
                if(in_array($v[1],$this->rulers))
                {
                    $this->rulers($key,$v);
                }
                elseif(in_array($v[1],$this->type))
                {
                    $this->type($key,$v);
                }
            }
        }
        dd($this->errors);

    }
    private function rulers($key,$var)
    {
        switch ($var[1])
        {
            case 'required':
                if(empty($_REQUEST[$key]))
                    $this->errors[]=empty($var['message'])?"$key 不能为空":$var['message'];
                break;
            case 'unique':
                $value=empty($_REQUEST[$key])?'':$_REQUEST[$key];
                $r=$this->model->select($key)->where(array($key=>$value))->get();
                if($r)
                    $this->errors[]=empty($var['message'])?"$key 已经存在":$var['message'];
                break;
        }
    }
    private function type($key,$var)
    {
        $min=empty($var['min'])?0:$var['min'];
        $max=empty($var['max'])?0:$var['max'];
        switch ($var[1])
        {
            case 'string':
                $pattern='/^[a-zA-z0-9]{'.$min.','.$max.'}$/';
                if(!preg_match($pattern,$_REQUEST[$key]))
                {
                    $this->errors[]=empty($var['message'])?"$key 必须是字符串":$var['message'];
                }
                break;
            case 'int':
                $pattern='/^\d{'.$min.','.$max.'}$/';
                if(!preg_match($pattern,$_REQUEST[$key]))
                {
                    $this->errors[]=empty($var['message'])?"$key 必须是整数":$var['message'];
                }
                break;
        }

    }
    private function  getRuler($rulers = array())
    {
        $arr=[];
        foreach ($rulers as $k=>$ruler)
        {
            foreach ($ruler as $key=>$value)
            {
                $arr[$ruler[0]][$k][$key]=$value;
            }
        }
        foreach ($arr as $key=>$value)
        {
            foreach ($value as $k=>$v)
            {
                foreach ($v as $m=>$n)
                {
                    if($n==$key)
                        unset($arr[$key][$k][$m]);
                }
            }
        }
        return $arr;

    }

}