<?php namespace addons\wefeemall\controller\index;

class Index extends Base
{

    public function index()
    {
        $title = mall_config('web_name');

        return view(VIEW_PATH . '/index/index/index.html', compact('title'));
    }

}