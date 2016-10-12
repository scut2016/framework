<?php
/**
 * 文件名：Config.php
  项目配置类
 */
namespace vendor\core;
class Config implements \ArrayAccess
{
    protected $path;
    protected $configs=[];//保存配置的数组
    public function __construct($path)
    {
        $path=str_replace('\\','/',$path);
        $this->path=$path;
    }

    public function offsetExists($offset)
    {
        return isset($this->configs[$offset]);

    }
//自动执行的代码 
    public function offsetGet($offset)
    {
        if(empty($this->configs[$offset]))
        {
            $filePath=$this->path.'/'.$offset.'.php';
            $config=require($filePath);
            $this->configs[$offset]=$config;
        }
        return $this->configs[$offset];
    }
//设置值执行
    public function offsetSet($offset, $value)
    {
        $this->configs[$offset]=$value;
    }
//unset
    public function offsetUnset($offset)
    {
        unset($this->configs[$offset]);
    }
}

