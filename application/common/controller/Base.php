<?php
/**
 * 基础控制器
 * 必须继承think\Controller.php
 */
namespace app\common\controller;
use think\Controller;
use think\facade\Session;
use app\common\model\ArtCate;
use app\common\model\Article;
use app\admin\common\model\Site;
use think\facade\Request;

class Base extends Controller
{
    /**
     * 初始化
     * 创建常量，公共方法
     * 在所有的方法之前被调用
     */
    protected function initialize()
    {
        // 检测站点是否关闭
        $this->is_open();
        // 显示分类导航
        $this->showNav();
        // 获取热门数据
        $this->getHotArt();
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
    //检测注册是否关闭:放在前台注册方法中调用
    public function is_reg()
    {
        //1.获取当前站点的注册状态
        $isReg = Site::where('status',1)->value('is_reg');

        //3. 如果已关闭注册,则直接跳转到首页
        if ($isReg == 0) {            

            $this->error('注册已关闭','index/index');
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

    // 检测站点是否关闭
    public function is_open(){
        // 1·获取当前站点状态
        $isOpen = Site::where('status',1)->value('is_open');
        // 2·如果站点已经关闭，只允许关闭前台
        if($isOpen==0 && Request::module()=='index'){
            // 关闭网站
            $info = <<< 'INFO'
<body style="background:#333"><h1 style="color:#eee;text-align:center;margin:200px;">站点维护中...</h1></body>
INFO;
            exit($info);
        }
    }

    // 根据阅读量PV来获取内容
    public function getHotArt(){
        $hotArtList = Article::where('status',1)->limit(12)->order('pv','desc')->select();
        $this->assign('hotArtList',$hotArtList);
    }
}
