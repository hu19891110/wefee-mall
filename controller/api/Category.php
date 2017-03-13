<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\model\MallCategories;
use think\Controller;

class Category extends Controller
{

    public function all()
    {
        $categories = MallCategories::where([
            ['fid' => 0], ['category_status' => 1]
        ])->order('category_sort', 'asc')->select();

        $arr = [];

        foreach ($categories as $category) {
            $tmp = $category->toArray();
            $child = [];
            foreach ($category->children()->where('category_status', 1)->select() as $item) {
                $child[] = $item->toArray();
            }
            $tmp['children'] = $child;
            $arr[] = $tmp;
        }

        return json($arr);
    }

    public function children()
    {
        $fid = request()->param('fid');

        $category = MallCategories::get($fid);

        if (! $category) {
            return json(['error' => 'no data.']);
        }

        $data = $category->toArray();
        $data['children'] = $category->children()->where('category_status', 1)->order('category_sort', 'asc')->select();

        return json($data);
    }

}