<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\User;

class Test extends Base
{
    public function test()
    {
        $data = [
            'name'=>'admin',
            'email'=>'dong@126.com',
            'mobile'=>'13199827263',
            'password'=>'123abc'
        ];
        $rule = 'app\common\validate\User';
        if(!$res = $this->validate($data,$rule)){
            return $res;
        }else{
            dump($res); 
        }
    }
    public function test1()
    {
        dump(User::select());
    }
}