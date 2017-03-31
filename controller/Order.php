<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallOrders;
use app\common\controller\Base;

class Order extends Base
{

    public function index()
    {
        $where = [];
        $params = [];
        if (request()->param('orderid') != '') {
            $params['orderid'] = request()->param('orderid');
            $where['orderid'] = ['like', '%'.request()->param('orderid').'%'];
        }

        $orders = MallOrders::where($where)->order('created_at', 'desc')->paginate(10, false, $params);

        $title = '订单列表';

        return view(VIEW_PATH . '/admin/order/index.html', compact('title', 'orders'));
    }

    public function info()
    {
        $order = MallOrders::where('id', request()->param('order_id'))->find();

        ! $order && $this->error('订单不存在');

        $title = '订单详情';

        return view(VIEW_PATH . '/admin/order/info.html', compact('title', 'order'));
    }

}