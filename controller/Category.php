<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallGoods;
use think\Validate;
use app\common\controller\Base;
use addons\wefeemall\model\MallCategories;

class Category extends Base
{

    public function index()
    {
        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $title = '商品分类列表';

        return view(VIEW_PATH . '/category/index.html', compact('title', 'categories'));
    }

    public function add()
    {
        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $title = '添加商品分类';

        return view(VIEW_PATH . '/category/add.html', compact('title', 'categories'));
    }

    public function create()
    {
        $data = $this->validator();

        MallCategories::create($data);

        $this->success('操作成功', aurl('wefeemall/category'));
    }

    public function edit()
    {
        $category = $this->getCategory();

        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $title = '编辑幻灯片';

        return view(VIEW_PATH . '/category/edit.html', compact('title', 'categories', 'category'));
    }

    public function update()
    {
        $category = $this->getCategory();

        $data = $this->validator();

        $category->save($data);

        $this->success('操作成功', aurl('wefeemall/category'));
    }

    protected function validator()
    {
        $data = request()->only([
            'fid', 'category_name', 'category_sort', 'category_icon', 'category_status',
        ]);

        $validator = new Validate([
            'fid|父分类' => 'require',
            'category_name|分类名' => 'require',
            'category_sort|排序' => 'require',
            'category_status|分类状态' => 'require',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function delete()
    {
        $category = $this->getCategory();

        MallGoods::destroy(['category_id' => $category->id]);

        MallCategories::destroy(['fid' => $category->id]);

        $category->delete();

        $this->success('操作成功');
    }

    protected function getCategory()
    {
        $category = MallCategories::get(request()->param('category_id'));

        if (! $category) {
            $this->error('商品分类不存在');
        }

        return $category;
    }

}