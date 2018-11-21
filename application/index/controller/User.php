<?php
namespace app\index\controller;

use app\common\controller\Base;
use think\facade\Request;
use app\common\model\User as UserModel;
use think\facade\Session;

class User extends Base
{
    // 注册页面
    public function register()
    {
        //检测注册是否关闭
        $this->is_reg();
        //检测是否已经登录
        $this->logined();
        $this->assign('title', '用户注册');
        return $this->fetch();
    }
    public function insert()
    {
        if (Request::isAjax()) {
            // 验证数据
              $data = Request::post(); // 要验证的数据
               $rule = 'app\common\validate\User'; // 自定义的验证规则
            // 开始验证
                $res = $this->validate($data, $rule);
            if ($res !== true) { // false
                return ['status'=>-1,'message'=>$res];
            } else { // true
                if ($user = UserModel::create($data)) {
                    // 注册成功后，实现自动登录
                    $res = UserModel::get($user->id);
                    Session::set('user_id',$res->id);
                    Session::set('user_name',$res->name);

                    return ['status'=>1,'message'=>'恭喜，注册成功！'];
                } else {
                    return ['status'=>0,'message'=>'注册失败，请检查！'];
                }
            }
        } else {
            return $this->error("请求类型错误！", "register");
        }
    }
    public function login()
    {
        $this->logined();
        return $this->fetch('login', ['title','用户登录']);
    }
    // 用户登录验证与查询
    public function loginCheck()
    {
        if (Request::isAjax()) {
            // 验证数据
              $data = Request::post(); // 要验证的数据
              $rule = [
                  'email|邮箱'      => 'require|email',
                  'password|密码'   => 'require|alphaNum',
              ]; // 自定义的验证规则
            // 开始验证
            $res = $this->validate($data, $rule);
            if ($res !== true) { // false
                return ['status'=>-1,'message'=>$res];
            } else { // true
                // 执行查询
                $result = UserModel::get(function ($query) use ($data) {
                    $query->where('email', $data['email'])->where('password',sha1($data['password']));
                });
                if ($result == null) {
                    return ['status'=>0,'message'=>'邮箱或密码不正确，请检查！'];
                } else {
                    // 将用户的数据写到session
                    Session::set('user_id',$result->id);
                    Session::set('user_name',$result->name);
                    return ['status'=>1,'message'=>'恭喜，登录成功！'];
                }
            }
        } else {
            return $this->error("请求类型错误！", "login");
        }
    }

    // 退出登录
    public function logout()
    {
        // Session::delete('user_id');
        // Session::delete('user_name');
        Session::clear();
        $this->success('退出成功','index/index');
    }
}
