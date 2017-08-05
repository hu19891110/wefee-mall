<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallGoods;
use addons\wefeemall\traits\LoginCheck;
use addons\wefeemall\model\MallCategories;

class Coupon extends Base
{
    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function index()
    {
        $request = request();
        $goods = null;
        $category = null;
        if ($request->param('goods_id') != '') {
            $goods = MallGoods::get($request->param('goods_id'));
            if (! $goods) {
                $this->error('商品不存在');
            }
        }
        if ($request->param('category_id') != '') {
            $category = MallCategories::get($request->param('category_id'));
            if (! $category) {
                $this->error('商品不存在');
            }
        }

        $title = '领取优惠券';

        return view(VIEW_PATH . '/index/coupon/index.html', compact('title', 'category', 'goods'));
    }

    public function receive()
    {
        $title = '领取优惠券';

        return view(VIEW_PATH . '/index/coupon/list.html', compact('title'));
    }

    public function member()
    {
        $where = [];
        if (request()->param('coupon_status') != '') {
            $where['status'] = request()->param('coupon_status');
        }

        $coupons = AuthManage::user()->coupons()->where($where)->select();

        $title = '我的优惠券';

        return view(VIEW_PATH . '/index/coupon/member.html', compact('title', 'coupons'));
    }

}