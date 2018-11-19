<?php
namespace app\common;
class Temp
{
    public function __construct($name = 'Peter'){
        $this->name = $name;
    }
    public function setName($name){
        $this->name = $name; 
    }
    public function getName(){
        return '方法是：'.__METHOD__.'属性是：'.$this->name;
    }
}