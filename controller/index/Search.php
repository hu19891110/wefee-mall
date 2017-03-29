<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\model\MallGoods;

class Search extends Base
{

    public function index()
    {
        $title = '搜索商品';

        return view(VIEW_PATH . '/index/search/index.html', compact('title'));
    }

    public function post()
    {
        $keys = htmlspecialchars(request()->param('keys'));

        $keys == '' && $this->error('请输入关键字');

        $goodses = MallGoods::where([
            'goods_name'   => ['like', '%'.request()->param('keys').'%'],
            'goods_status' => 1,
        ])->paginate(10);

        $title = '搜索结果';

        return view(VIEW_PATH . '/index/search/result.html', compact('title', 'keys', 'goodses'));
    }

}