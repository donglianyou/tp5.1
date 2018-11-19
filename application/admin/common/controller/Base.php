<?php
/**
 * 后台公共控制器
 */
namespace app\admin\common\controller;
use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    // 初始化方法
    protected function initialize()
    {

    }
    /**
     * 检测用户是否登录
     * 1·调用位置：后台入口admin.php/index/index()
     */
    public function isLogin()
    {
        if(!Session::has('admin_id')){
            $this->error("请先登录！","admin/user/login");
        }
    }
}