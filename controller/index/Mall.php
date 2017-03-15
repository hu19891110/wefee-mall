<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;

class Mall extends Base
{

    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function index()
    {
        $user = AuthManage::user();

        $total = 0;

        foreach ($user->malls as $item) {
            $total += $item->goods->goods_now_cost;
        }

        $title = '购物车';

        return view(VIEW_PATH . '/index/mall/index.html', compact('title', 'total', 'user'));
    }

}