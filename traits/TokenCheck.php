<?php namespace addons\wefeemall\traits;

use think\Session;

trait TokenCheck
{

    protected function tokenCheck()
    {
        Session::get('__token__') != request()->post('__token__') && $this->error('会话已过期！');
    }

}