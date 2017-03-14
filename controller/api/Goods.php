<?php namespace addons\wefeemall\controller\api;

use think\Controller;
use addons\wefeemall\model\MallGoods;

class Goods extends Controller
{

    public function search()
    {
        $keys = request()->param('key_words');

        if ($keys == '') {
            json(['error' => '请输入要搜索的关键字']);
        }

        $goods = $this->selectGoods([
            'goods_name' => ['like', '%'.$keys.'%'],
            'goods_status' => 1,
            'published_at' => ['<', date('Y-m-d H:i:s')],
        ]);

        return json($goods->toArray());
    }

    public function select()
    {
        $request = request();
        $where = [];
        if ($request->param('category_id') != '') {
            $where['category_id'] = $request->param('category_id');
        }

        $goods = $this->selectGoods($where);

        return json($goods->toArray());
    }

    protected function selectGoods($where = [], $order = 'created_at desc')
    {
        return $goods = MallGoods::where($where)->field([
            'id', 'goods_name', 'category_id', 'goods_photos', 'goods_stock',
            'goods_origin_cost', 'goods_now_cost', 'goods_score', 'goods_sales',
        ])->order($order)->paginate(8);
    }

}