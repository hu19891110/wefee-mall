<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\model\MallGoods;
use addons\wefeemall\controller\index\Base;
use addons\wefeemall\transform\GoodsTransformer;

class Search extends Base
{

    public function index()
    {
        $keys = htmlspecialchars(request()->param('keys'));

        $keys == '' && $this->error('请输入关键字');

        $goodses = MallGoods::where([
            'goods_name'   => ['like', '%'.request()->param('keys').'%'],
            'goods_status' => 1,
        ])->paginate(10);

        $arr = [];

        foreach ($goodses as $item) {
            $arr[] = (new GoodsTransformer())->transform($item);
        }

        return $this->success('获取成功', '', $arr);
    }

}