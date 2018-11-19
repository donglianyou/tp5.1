<?php
namespace app\common\validate;
use think\Validate;

class User extends Validate
{
    protected $rule = [
        'name|用户名'     => 'require|length:5,20|chsAlphaNum',
        'email|邮箱'    => 'require|email|unique:zh_user',
        'mobile|手机号' => 'require|mobile|unique:zh_user|number',
        'password|密码' => 'require|length:6,20|alphaNum|confirm',
    ];
}