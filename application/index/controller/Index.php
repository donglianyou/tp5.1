<?php
namespace app\index\controller;

use app\common\controller\Base; //导入公共控制器
use app\common\model\ArtCate;
use app\common\model\Article;
use app\common\model\Comment;
use think\facade\Request;
use think\facade\Session;
use think\Db;

class Index extends Base
{
    public function index()
    {
        // 全局查询条件
        $map = []; // 将所有的查询条件封装到这个数组中
        // 条件1
        $map[] = ['status','=',1];
        // 实现搜索功能
        $keywords = Request::param('keywords');
        if (!empty($keywords)) {
            // 条件2
            $map[] = ['title','like','%'.$keywords.'%'];
        }
        // 分类信息的显示
        $cateId = Request::param('cate_id');
        // 如果存在这个分类id
        if (isset($cateId)) {
            // 条件3
            $map[] = ['cate_id','=',$cateId];
            $res = ArtCate::get($cateId);
            // 列表信息的分页显示
            $artList = Article::where($map)
            ->order('create_time', 'desc')->paginate(3, false, ['query'=>Request::param()]);
            $this->assign('cateName', $res->name);
        } else {
            $this->assign('cateName', '全部文章');
            $artList = Article::where($map)->order('create_time', 'desc')->paginate(3, false, ['query'=>Request::param()]);
        }
        $this->assign('empty', '<h3>没有文章</h3>');
        $this->assign('artList', $artList);
        return view('index', ['name'=>'社区问答']);
    }
    public function insert()
    {
        // 1·登录之后才允许发布文章、
        $this->isLogin();
        // 2·设置页面标题
        $this->assign('title', '发布文章');
        // 3·获取栏目信息
        $cateList = ArtCate::all();
        if (count($cateList)>0) {
            // 将查询到的栏目信息赋值给模板
            $this->assign('catelist', $cateList);
        } else {
            $this->error('请先添加栏目', 'index/index');
        }
        // 4·发布界面渲染
        return view('insert');
    }
    public function save()
    {
        // 判断提交类型
        if (Request::isPost()) {
            // 获取用户提交信息
            $data = Request::post();
            $res = $this->validate($data, 'app\common\validate\Article');
            if ($res !==true) {
                // 验证失败
                $this->error($res);
            } else {
                // 验证成功
                // 获取上传图片的信息
                $file = Request::file('title_img');
                // 文件信息验证成功后再上传到服务器指定目录,以public为起始目录
                $info = $file->validate([
                    'size'=>1000000,
                    'ext'=>'jpeg,jpg,png,gif',
                ])->move('uploads/');
                if ($info) {
                    $data['title_img'] = $info->getSaveName();
                } else {
                    $this->error($this->getError());
                }
                // 将数据写入到数据表中
                if (Article::create($data)) {
                    $this->success('文章发布成功', 'index/index');
                } else {
                    $this->error('文章保存失败');
                }
            }
        } else {
            $this->error('请求类型错误');
        }
    }

    public function detail()
    {
        $artId = Request::param('id');
        $art = Article::get(function ($query) use ($artId) {
            $query->where('id', $artId)->setInc('pv');
        });
        // 添加评论
        $this->assign('commentList',Comment::all(function($query) use ($artId){
            $query->where('status',1)->where('art_id',$artId)->order('create_time','desc');
        }));

        if (!is_null($art)) {
            $this->assign('art', $art);
            $this->assign('title', '详情页');
            return $this->fetch('detail');
        } else {
            return $this->error('没有文章，请返回');
        }
    }
    // 收藏
    public function fav()
    {
        if (!Request::isAjax()) {
            return ['status'=>-1,'message'=>'请求类型错误！'];
        }
        // 获取从前端传递过来的数据
        $data = Request::param();
        // 判断用户是否登录
        if(empty($data['session_id'])){
            return ['status'=>-2,'message'=>'请登录后再收藏！'];
        }
        $map[] = ['user_id','=',$data['user_id']];
        $map[] = ['art_id','=',$data['article_id']];
        $fav = Db::table('zh_user_fav')->where($map)->find();
        if (is_null($fav)) {
            Db::table('zh_user_fav')->data([
                'user_id'=>$data['user_id'],
                'art_id'=>$data['article_id'],
            ])->insert();
            return ['status'=>1,'message'=>'收藏成功'];
        } else {
            Db::table('zh_user_fav')->where($map)->delete();
            return ['status'=>0,'message'=>'已取消'];
        }
    }
    // 点赞
    public function ok()
    {
        if (!Request::isAjax()) {
            return ['status'=>-1,'message'=>'请求类型错误！'];
        }
        // 获取从前端传递过来的数据
        $data = Request::param();
        // 判断用户是否登录
        if(empty($data['session_id'])){
            return ['status'=>-2,'message'=>'请登录后再点赞！'];
        }
        $map[] = ['user_id','=',$data['user_id']];
        $map[] = ['art_id','=',$data['article_id']];
        $fav = Db::table('zh_user_like')->where($map)->find();
        if (is_null($fav)) {
            Db::table('zh_user_like')->data([
                'user_id'=>$data['user_id'],
                'art_id'=>$data['article_id'],
            ])->insert();
            return ['status'=>1,'message'=>'喜欢'];
        } else {
            Db::table('zh_user_like')->where($map)->delete();
            return ['status'=>0,'message'=>'不喜欢'];
        }
    }
    public function insertComment(){
        
        if(Request::isAjax()){
            // 1·获取到评论内容
            $data = Request::param();
            // 未登录
            if(!Session::has('user_id')){
                return ['status'=>-1,'message'=>'请先登录'];
            }
            // 2·将用户留言存到表中
            if(Comment::create($data,true)){
                return ['status'=>1,'message'=>'评论发表成功'];
            }
            // 失败
            return ['status'=>0,'message'=>'评论发表失败'];
        }
    }
    public function showComment(){
        $artId = Request::param('id');
        $art = Article::get(function ($query) use ($artId) {
            $query->where('id', $artId)->setInc('pv');
        });
        // 添加评论
        $this->assign('commentList',Comment::all(function($query) use ($artId){
            $query->where('status',1)->where('art_id',$artId)->order('create_time','desc');
        }));

        if (!is_null($art)) {
            $this->assign('art', $art);
            return $this->fetch('comment');
        }
        
    }
}
