<?php namespace addons\wefeemall\controller\index;

use think\Hook;
use addons\wefeemall\lib\Teegon;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;

class Pay extends Base
{
    use LoginCheck;

    protected $instance = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->instance = new Teegon('https://api.teegon.com/');

        /** 绑定钩子 */
        Hook::add('payed', [
            \addons\wefeemall\behavior\Payed::class,
        ]);
    }

    public function pay()
    {
        $this->loginCheck();

        /** 获取订单 */
        $order = AuthManage::user()->orders()->where('orderid', request()->param('orderid'))->find();

        ! $order && $this->error('订单不存在');

        $result = $this->instance->post('v1/charge/', array(
            'client_id'     => mall_config('teegon_client_id'),
            'client_secret' => mall_config('teegon_client_secret'),
            'amount'        => $order->now_charge,
            'return_url'    => mall_url('index.pay/payResult'),
            'channel'       => 'wxpay_jsapi',
            'order_no'      => $order->orderid,
            'subject'       => '订单：'.$order->orderid,
        ));

        $arr = json_decode($result, true);

        if (isset($arr['error'])) {
            $this->error($arr['error']);
        }
    }

    public function payResult()
    {
        $this->loginCheck();

        $s = $this->instance->verify_return();

        if ($s == 0) {
            $this->success('支付成功', mall_url('index.member/order'));
        } else {
            $this->error($s['error_msg'], mall_url('index.member/order'));
        }
    }

}