<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\controller\index\Base;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallGoods;
use addons\wefeemall\model\MallMalls;
use addons\wefeemall\traits\LoginCheck;

class Mall extends Base
{

    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function add()
    {
        $num = request()->param('num');

        $goods = MallGoods::get(request()->param('goods_id'));

        if (! $goods) {
            $this->error('商品不存在！');
        }

        /** 判断购物车是否存在该商品 */
        $mall = MallMalls::where([
            'user_id'  => AuthManage::id(),
            'goods_id' => $goods->id,
        ])->find();

        if ($mall) {
            if ($mall->goods_num != $num) {
                $mall->save(['goods_num' => $num]);
            }
            $this->success('添加成功');
        }

        $mall = new MallMalls;
        $mall->save([
            'user_id'   => AuthManage::id(),
            'goods_id'  => $goods->id,
            'goods_num' => $num,
        ]);

        $this->success('操作成功');
    }

    public function num()
    {
        $this->success(AuthManage::user()->malls()->count());
    }

    public function delete()
    {
        $mall = AuthManage::user()->malls()->where('id', request()->param('mall_id'))->find();

        if (! $mall) {
            $this->error('记录不存在！');
        }

        $mall->delete();

        $this->success('成功');
    }

}