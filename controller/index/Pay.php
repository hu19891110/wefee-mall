<?php namespace addons\wefeemall\controller\index;

use think\Hook;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\behavior\Payed;
use addons\wefeemall\traits\LoginCheck;

class Pay extends Base
{
    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();
        Hook::add('payed', [Payed::class]);
    }

    public function pay()
    {
        $this->loginCheck();

        /** 获取订单 */
        $order = AuthManage::user()->orders()->where('orderid', request()->param('orderid'))->find();
        !$order && $this->error('订单不存在');

        /** 暂未处理 */
        $this->success('暂无支付功能');
    }

    public function payResult()
    {
        $this->loginCheck();

        /** 暂未处理 */
    }

    public function payNotify()
    {
        /** 暂未处理 */
    }

}