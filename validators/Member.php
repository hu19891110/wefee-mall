<?php namespace addons\wefeemall\validators;

use think\Validate;

class Member extends Validate
{

    protected $rule = [
        'nickname|呢称' => 'require',
        'username|手机号' => 'require|mobile|unique:mall_users',
        'password|密码' => 'require|min:6|max:16|confirm',
    ];

    protected $message = [
        'nickname.require' => '请输入呢称',
        'username.require' => '请输入手机号',
        'username.mobile'  => '请输入有效手机号',
        'username.unique'  => '手机号已经存在',
        'password.require' => '请输入密码',
        'password.min' => '密码长度在6-16个字符',
        'password.max' => '密码长度在6-16个字符',
        'password.confirm' => '两次输入密码不一致',
    ];

    protected function mobile($value, $rule)
    {
        return 1 === preg_match('/^(((13[0-9]{1})|(15[0-35-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/', $value) ? true : false;
    }

}