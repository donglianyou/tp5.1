<?php
namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\common\model\Cate as CateModel;
use think\facade\Request;
use think\facade\Session;

class Cate extends Base
{
    // 分类管理首页
    public function index()
    {
        // 检查用户是否登录
        $this->isLogin();
        // 登录成功之后直接跳转分类管理界面
        return $this->redirect('cateList');
    }
    // 分类列表
    public function cateList()
    {
        // 1·检查用户是否登录
        $this->isLogin();
        // 2·获取到所有的分类
        $cateList = CateModel::all();
        // 3·设置模板变量
        $this->assign('title', '分类管理');
        $this->assign('empty', '<span style="color:red;">没有分类</span>');
        $this->assign('cateList', $cateList);
        return $this->fetch('catelist');
    }
    // 渲染编辑分类界面
    public function cateEdit()
    {
        // 1·获取到分类id
        $cateid = Request::param('id');
        // 2·根据主键查询要更新的分类信息
        $cateInfo = CateModel::where('id', $cateid)->find();
        // 3·设置模板变量
        $this->assign('title', '编辑分类');
        $this->assign('cateInfo', $cateInfo);
        return $this->fetch('cateedit');
    }
    // 执行编辑操作
    public function doEdit()
    {
        //1.获取栏目提交的更新信息
        $data = Request::param();

        $id = $data['id'];  //取出更新主键

        //2.删除主键字段,封装出要更新的字段数组
        unset($data['id']);

        //3.执行更新操作
        if (CateModel::where('id', $id)->data($data)->update()) {
            return $this->success('更新成功', 'cateList');
        }

        //3. 更新失败提示
        $this->error('没有更新或更新失败');
    }
    //执行栏目的删除操作
	public function doDelete()
	{
		//1.获取要删除的数据主键
		$id = Request::param('id');

		//2.执行删除操作
		if(CateModel::where('id',$id)->delete()){
			return $this->success('删除成功','cateList');
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
