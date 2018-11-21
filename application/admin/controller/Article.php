<?php
namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use app\admin\common\model\Cate;
use think\facade\Request;
use think\facade\Session;

class Article extends Base
{
    // 文章首页
    public function index()
    {
        // 检查用户是否登录
        $this->isLogin();
        // 登录成功之后直接跳转文章管理界面
        return $this->redirect('artList');
    }
    // 文章列表
    public function artList()
    {
        // 1·检查用户是否登录
        $this->isLogin();
        // 2·获取当前用户id和用户级别
        $userId = Session::get('user_id');
        $isAdmin = Session::get('is_admin');
        // 3·获取当前用户发布的文章
        $artList = ArtModel::where('user_id',$userId)->paginate(5);
        // 4·如果是超级管理员，获取全部的文章
        if($isAdmin ==1){
            $artList = ArtModel::paginate(5);
        }
        // 5·设置模板变量
        $this->assign('title', '文章管理');
        $this->assign('empty', '<span style="color:red;">没有分类</span>');
        $this->assign('artList', $artList);
        return $this->fetch('artlist');
    }
    // 渲染编辑分类界面
    public function artEdit()
    {
        // 1·获取到分类id
        $artid = Request::param('id');
        // 2·根据主键查询要更新的分类信息
        $artInfo = ArtModel::where('id', $artid)->find();

        // 3·获取栏目信息
        $cateList = Cate::all();

        // 4·设置模板变量
        $this->assign('title', '编辑文章');
        $this->assign('cateList', $cateList);
        $this->assign('artInfo', $artInfo);
        return $this->fetch('artedit');
    }
    // 执行编辑操作
    public function doEdit()
    {
        //1.获取栏目提交的更新信息
        $data = Request::param();

        $id = $data['id'];  //取出更新主键

        // 2·获取上传图片的信息
        $file = Request::file('title_img');
        // 文件信息验证成功后再上传到服务器指定目录,以public为起始目录
        $info = $file->validate([
            'size'=>1000000,
            'ext'=>'jpeg,jpg,png,gif',
        ])->move('uploads/');
        if ($info) {
            $data['title_img'] = $info->getSaveName();
        } else {
            $this->error($file->getError());
        }
        // 将数据写入到数据表中
        if (ArtModel::update($data)) {
            $this->success('文章更新成功', 'artList');
        } else {
            $this->error('文章更新失败');
        }
    }
    //执行栏目的删除操作
	public function doDelete()
	{
		//1.获取要删除的数据主键
		$artId = Request::param('id');

		//2.执行删除操作
		if(ArtModel::destroy($artId)){
			return $this->success('删除成功','artList');
		}

		//3. 删除失败提示
		$this->error('删除失败');

    }
    // 渲染添加分类页面
    public function cateAdd(){
        return $this->fetch('cateadd',['title'=>'添加分类']);
    }
    // 添加分类
    public function doAdd(){
        // 1·获取到要添加的分类信息
        $data = Request::param();
        // 2·添加操作
        if(CateModel::create($data)){
            return $this->success('添加成功','cateList');
        }
        // 3·失败
        $this->error('新增失败');
    }
}
