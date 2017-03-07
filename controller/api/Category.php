<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\model\MallCategories;
use think\Controller;

class Category extends Controller
{

    public function all()
    {
        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $arr = [];

        foreach ($categories as $category) {
            $tmp = $category->toArray();
            $child = [];
            foreach ($category->children as $item) {
                $child[] = $item->toArray();
            }
            $tmp['children'] = $child;
            $arr[] = $tmp;
        }

        return json($arr);
    }

}