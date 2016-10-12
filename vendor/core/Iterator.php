<?php
/**
 * 文件名：Iterator.php
 * 文件说明:
 * 时间: 2016/10/12.11:12
 */

namespace vendor\core;

use vendor\base\Model;

class Iterator implements \Iterator
{
    protected $results=[];//全部结果集
    protected $index=0;//指针索引
    
    public function __construct($results)
    {
        $this->results=$results;
    }
    
    public function current()
    {
      return $this->results[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->results[$this->index]);
    }

    public function rewind()
    {
        $this->index=0;
    }

    

}