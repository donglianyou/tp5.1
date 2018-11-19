<?php
/**
 * 基础控制器
 * 必须继承think\Controller.php
 */
namespace app\common\controller;
use think\Controller;
use think\facade\Session;
use app\common\model\ArtCate;

class Base extends Controller
{
    /**
     * 初始化
     * 创建常量，公共方法
     * 在所有的方法之前被调用
     */
    protected function initialize()
    {
        // 显示分类导航
        $this->showNav();
    }
    // 防止重复登录
    public function logined()
    {
        if(Session::has('user_id')){
            $this->error("客官，您已经登录了！","index/index");
        }
    }
    // 检查是否未登录
    public function isLogin()
    {
        if(!Session::has('user_id')){
            $this->error("客官，您是不是忘记登录了！","index/index");
        }
    }
    // 显示分类导航
    protected function showNav()
    {
        // 1·查询分类表获取到所有的分类信息
        $cateList = ArtCate::all(function($query){
            $query->where('status',1)->order('sort','asc');
        });
        // 2·将分类信息赋值给模板
        $this->assign('cateList',$cateList);
    }
}
