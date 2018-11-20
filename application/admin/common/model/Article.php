<?php
/**
 * 用户表模型
 */
namespace app\admin\common\model;
use think\Model;
class Article extends Model
{
    protected $pk = 'id'; // 默认主键
    protected $table = 'zh_article'; // 默认数据表
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y年m月d日';
}
