<?php

namespace app\index\controller;

use think\Controller;
use app\common\controller\Base;
use think\Request;
use app\common\model\Shop as Shops;
use Db;

class Shop extends Controller
{
    public function index()
    {
        // 获取shop表中的所有数据
        // $shop = Shops::select();
        // $shop = Db::table('zh_shop')->find(2);
        $shop = Db('zh_shop')->select();
        halt($shop);
        return $this->fetch('index',compact('shop'));
    }
}
