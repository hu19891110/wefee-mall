<?php namespace addons\wefeemall\controller\index;

use think\Controller;

class Category extends Controller
{

    public function index()
    {
        $title = '全部分类';

        return view(VIEW_PATH . '/index/category/index.html', compact('title'));
    }

}