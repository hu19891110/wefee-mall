<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\model\MallGoods;
use think\Controller;

class Goods extends Controller
{

    public function search()
    {
        $keys = request()->param('key_words');

        if ($keys == '') {
            json(['error' => '请输入要搜索的关键字']);
        }

        $goods = MallGoods::where('goods_name', 'like', '%'.$keys.'%')
                            ->field('id,goods_name')
                            ->paginate(15);

        return json($goods->toArray());
    }

}