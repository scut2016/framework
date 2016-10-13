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
    protected $len=['min','max'];
    protected $errors=[];
    protected $model=null;
    function update($model,$rulers = array())
    {
        $this->model=$model;
        $result=$this->getRuler($rulers);
        foreach ($result as $key=>$value)
        {
            foreach ($value as $k=>$v)
            {
                if(in_array($v[1],$this->rulers))
                {
                    $this->rulers($key,$v);
                }

                elseif(in_array($v[1],$this->type))
                     $this->type($key,$v);
            }
        }
        dd($this->errors);
    }
    private function type($key,$arr)
    {
        $min=empty($arr['min'])?'0':$arr['min'];
        $max=empty($arr['max'])?'0':$arr['max'];
        switch($arr[1])
        {

            case 'string':
                $pattern="/^[a-zA-Z0-9_]{".$min.",".$max."}$/";
               dd($pattern);
                if(!preg_match($pattern,$_REQUEST[$key]))
                {
                    $this->errors[]= empty($arr['message'])?"$key 必须是字符串":$arr['message'];
                }

                break;
            case 'int':
                if(!is_integer($_REQUEST[$key]))
                    $this->errors[]= empty($arr['message'])?"$key 必须是整数":$arr['message'];
                break;
            case 'double':
                if(!is_integer($_REQUEST[$key]))
                    $this->errors[]= empty($arr['message'])?"$key 必须是小数":$arr['message'];
                break;
        }



    }

    private function rulers($key,$arr)
    {
        switch($arr[1])
        {
            case 'required' :
                if(empty($_REQUEST[$key]))
                {
                    $this->errors[]= empty($arr['message'])?"$key 不能为空":$arr['message'];
                }
                break;
            case 'unique':
                $value=empty($_REQUEST[$key])?'':$_REQUEST[$key];
                $r=$this->model->select($key)->where(array($key=>$value))->get();
                if($r)
                    $this->errors[]= empty($arr['message'])?"$key 已经存在":$arr['message'];
                break;
        }
    }
//对规则数组进行分类处理
    private function getRuler($rulers = array())
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
