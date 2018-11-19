<?php
namespace app\common\validate;
use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title|标题'     => 'require|length:5,30|chsAlphaNum',
        // 'title_img|标题图片'    => 'require',
        'content|手机号' => 'require',
        'user_id|作者' => 'require',
        'cate_id|栏目名称' => 'require',
    ];
}