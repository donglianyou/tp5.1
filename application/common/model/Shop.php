<?php

namespace app\common\model;

use think\Model;

class Shop extends Model
{
    // protected $pk = 'id'; // 默认主键
    protected $table = 'zh_shop'; // 默认数据表
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y年m月d日';
}
