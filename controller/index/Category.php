<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\model\MallCategories;
use think\Controller;

class Category extends Controller
{

    public function index()
    {
        $title = '全部分类';

        return view(VIEW_PATH . '/index/category/index.html', compact('title'));
    }

    public function info()
    {
        $category = MallCategories::get(request()->param('category_id'));

        if (! $category) {
            $this->error('分类不存在');
        }

        $goods = $category->goods()->where([
            'goods_status' => 1,
            'published_at' => ['<', date('Y-m-d H:i:s')],
        ])->order('created_at', 'desc')->paginate(8);

        $title = $category->category_name;

        return view(VIEW_PATH . '/index/category/info.html', compact('title', 'goods', 'category'));
    }

}