<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\controller\index\Base;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;
use addons\wefeemall\transform\OrderTransformer;

class Order extends Base
{

    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function select()
    {
        $where = [];
        if (intval(request()->param('order_status')) > 0) {
            $where['order_status'] = (int) request()->param('order_status');
        }

        $orders = AuthManage::user()->orders()->where($where)->order('created_at', 'desc')->paginate(5);

        if (! $orders) {
            return [];
        }

        $data = $orders->toArray();
        $box  = [];
        foreach ($orders as $item) {
            $box[] = (new OrderTransformer())->transform($item);
        }
        $data['data'] = $box;

        return json($data);
    }

}