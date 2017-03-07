<?php namespace addons\wefeemall\controller;

use app\common\controller\Base;

class Index extends Base
{

    public function index()
    {
        $title = '微菲商城 - PowerBy Wefee.cc 文档地址：wefee.io';

        return view(VIEW_PATH . '/admin/index/index.html', compact('title'));
    }

}