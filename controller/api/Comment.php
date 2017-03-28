<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\model\MallGoods;
use addons\wefeemall\transform\CommentTransformer;
use addons\wefeemall\transform\UserTransformer;
use think\Controller;

class Comment extends Controller
{

    public function select()
    {
        $goods = MallGoods::get(request()->param('goods_id'));

        if (! $goods) {
            return json(['error' => '商品不存在']);
        }

        $where = [];
        $where['comment_status'] = 1;
        if (request()->param('type') == 'image') {
            $where['comment_photos'] = ['<>', ""];
        }

        $comments = $goods->comments()->where($where)->order('created_at', 'desc')->paginate(10);

        return json($this->transform($comments));
    }

    protected function transform($model)
    {

        $data = $model->toArray();
        $data['data'] = [];

        foreach ($model as $item) {
            $tmp = (new CommentTransformer())->transform($item);
            /** 用户信息 */
            $tmp['user'] = (new UserTransformer())->transform($item->user);
            $data['data'][] = $tmp;
        }

        return $data;
    }

}