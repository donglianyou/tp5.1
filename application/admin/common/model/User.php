<?php
/**
 * 用户表模型
 */
namespace app\admin\common\model;
use think\Model;
class User extends Model
{
    protected $pk = 'id'; // 默认主键
    protected $table = 'zh_user'; // 默认数据表
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y年m月d日';

    // 获取器(获取用户状态显示方式)
    public function getStatusAttr($value)
    {
        $status = ['1'=>'启用','0'=>'禁用'];
        return $status[$value];
    }
    public function getIsAdminAttr($value)
    {
        $status = ['1'=>'管理员','0'=>'注册会员'];
        return $status[$value];
    }

    // 修改器(设置密码加密)
    public function setPasswordAttr($value)
    {
        return sha1($value);
    }
}
