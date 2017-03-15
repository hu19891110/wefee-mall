<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\model\MallGoods;

class Comment extends Base
{

    public function index()
    {
        $goods = MallGoods::get((int) request()->param('goods_id'));

        if (! $goods) {
            $this->error('商品不存在');
        }

        $comments = $goods->comments()->where('comment_status', 1)->order('created_at', 'desc')->paginate(10);

        $title = '商品《' . $goods->goods_name . '》的评价';

        return view(VIEW_PATH . '/index/comment/index.html', compact('title', 'comments', 'goods'));
    }

}