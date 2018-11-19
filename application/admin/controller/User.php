<?php
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;

class User extends Base
{
    // 渲染登录界面
    public function login()
    {
        $this->assign('title','管理员登录');
        return $this->fetch();
    }
    
    // 验证后台登录
    public function checklogin()
    {
        $data = Request::param();
        // 查询条件
        $map[] = ['email','=',$data['email']];
        $map[] = ['password','=',sha1($data['password'])];
        $result = UserModel::where($map)->find();
        if($result){
            Session::set('admin_id',$result['id']);
            Session::set('admin_name',$result['name']);
            Session::set('admin_level',$result['is_admin']);
            $this->success('登录成功','admin/index/index');
        }
        $this->error('登录失败');
    }
}