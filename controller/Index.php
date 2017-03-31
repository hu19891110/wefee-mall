<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallOrders;
use addons\wefeemall\model\MallUsers;
use app\common\controller\Base;

class Index extends Base
{

    public function index()
    {
        $title = '微菲商城 - PowerBy Wefee.cc 文档地址：wefee.io';

        /** 新用户 */
        $newUserNum = MallUsers::where('created_at', '>=', date('Y-m-d'))->count();

        /** 新订单 */
        $newOrderNum = MallOrders::where('created_at', '>=', date('Y-m-d'))->count();

        /** 新售后订单 */
        $newRefundNum = 0;

        /** 订单总额 */
        $newOrderTotal = (int) MallOrders::where('created_at', '>=', date('Y-m-d'))->avg('now_charge');

        /** 新订单记录 */
        $lastOrderRecords = MallOrders::order('created_at', 'desc')->limit(8)->select();

        /** 新退款记录 */
        $lastRefundRecords = [];

        return view(VIEW_PATH . '/admin/index/index.html', compact(
            'title', 'newOrderTotal', 'newRefundNum', 'newOrderNum', 'newUserNum',
            'lastOrderRecords', 'lastRefundRecords'
        ));
    }

}