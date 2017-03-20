<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallGoods;
use addons\wefeemall\traits\LoginCheck;

class Order extends Base
{
    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function create()
    {
        $mall = $this->getMall();

        $user = AuthManage::user();

        /** 默认地址 */
        $address = $user->address()->order('is_default', 'desc')->find();

        $title = '下订单';

        return view(VIEW_PATH . '/index/order/create.html', compact('title', 'address', '', 'user', 'mall'));
    }

    protected function getMall()
    {
        if (! request()->isPost() && session('mall')) {
            return session('mall');
        }

        $id  = request()->param('id/a');
        $num = request()->param('num/a');

        if (! $id || ! $num) {
            $this->error('参数错误');
        }

        $mall = [];

        foreach ($id as $key => $item) {
            $goods = MallGoods::get($item);

            !$goods && $this->error('商品不存在');

            $mall[] = [
                'num' => $num[$key],
                'goods' => $goods
            ];
        }

        session('mall', $mall);

        return $mall;
    }

}