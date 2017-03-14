<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\model\MallGoods;
use think\Controller;

class Goods extends Controller
{

    public function info()
    {
        $goods = MallGoods::get(request()->param('goods_id'));

        if (! $goods) {
            $this->error('商品不存在');
        }

        $title = $goods->goods_name;

        return view(VIEW_PATH . '/index/goods/info.html', compact('title', 'goods'));
    }

}