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

    public function charts()
    {
        $dataSource = [];
        $startDate = strtotime('-30 days');
        while ($startDate < time()) {
            $count = (int) MallOrders::where('created_at', '>=', date('Y-m-d', $startDate))
                ->where('created_at', '<', date('Y-m-d', $startDate + 3600*24))
                ->count();
            $total = (int) MallOrders::where('created_at', '>=', date('Y-m-d', $startDate))
                ->where('created_at', '<', date('Y-m-d', $startDate + 3600*24))
                ->where('order_status', '>=', 5)
                ->avg('now_charge');

            $dataSource['label'][] = date('m/d', $startDate);
            $dataSource['data'][]  = $count;
            $dataSource['total'][] = $total;

            $startDate += 3600 * 24;
        }

        $title = '订单统计';

        return view(VIEW_PATH . '/admin/order/charts.html', compact('title', 'dataSource'));
    }

}