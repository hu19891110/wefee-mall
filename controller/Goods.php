<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallCategories;
use addons\wefeemall\model\MallGoods;
use app\common\controller\Base;
use think\Validate;

class Goods extends Base
{

    public function index()
    {
        $where['goods_status'] = ['<>', -9];
        $request = request();
        $queryParams = [];
        if ($request->has('goods_name') && $request->param('goods_name') != '') {
            $where['goods_name'] = ['like', "%".$request->param('goods_name')."%"];
            $queryParams['goods_name'] = $request->param('goods_name');
        }
        if ($request->has('category_id') && $request->param('category_id') != 0) {
            $where['category_id'] = $request->param('category_id');
            $queryParams['category_id'] = $request->param('category_id');
        }

        $goods = MallGoods::where($where)->order('created_at', 'desc')->paginate(15, false, ['query' => $queryParams]);

        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $title = '商品列表';

        return view(VIEW_PATH . '/admin/goods/index.html', compact('title', 'categories', 'goods'));
    }

    public function add()
    {
        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $title = '添加商品';

        return view(VIEW_PATH . '/admin/goods/add.html', compact('title', 'categories'));
    }

    public function create()
    {
        $data = $this->validator();

        MallGoods::create($data);

        $this->success('操作成功');
    }

    public function edit()
    {
        $goods = $this->getGoods();

        $categories = MallCategories::where('fid', 0)->order('category_sort', 'asc')->select();

        $title = '编辑商品';

        return view(VIEW_PATH . '/admin/goods/edit.html', compact('title', 'categories', 'goods'));
    }

    public function update()
    {
        $goods = $this->getGoods();

        $data = $this->validator();

        $goods->save($data);

        $this->success('操作成功');
    }

    protected function validator()
    {
        $data = request()->only([
            'category_id', 'goods_name', 'goods_photos',
            'goods_stock', 'goods_origin_cost', 'goods_now_cost',
            'goods_status', 'goods_addition_params', 'goods_score',
            'goods_sales', 'published_at', 'goods_description',
        ]);

        $params = [];
        $tmpName = request()->param('goods_addition_params_name/a');
        $tmpValue = request()->param('goods_addition_params_value/a');
        if ($tmpName) {
            foreach ($tmpName as $key => $value) {
                $params[] = [
                    'name' => $value,
                    'value' => $tmpValue[$key],
                ];
            }
        }
        $data['goods_addition_params'] = $params;

        $validator = new Validate([
            'category_id|分类' => 'require',
            'goods_name|商品名' => 'require',
            'goods_photos|商品图片' => 'require',
            'goods_stock|库存' => 'require',
            'goods_origin_cost|原价' => 'require',
            'goods_now_cost|现价' => 'require',
            'goods_status|状态' => 'require',
            'goods_sales|销量' => 'require',
            'published_at|上线时间' => 'require',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function delete()
    {
        $goods = $this->getGoods();

        $goods->save([
            'deleted_at' => date('Y-m-d H:i:s'),
            'goods_status' => -9,
        ]);

        $this->success('操作成功');
    }

    public function info()
    {
        $goods = $this->getGoods();

        $title = '商品详情';

        return view(VIEW_PATH . '/admin/goods/info.html', compact('title', 'goods'));
    }

    public function getGoods()
    {
        $goods = MallGoods::get(request()->param('goods_id'));

        if (! $goods) {
            $this->error('商品不存在');
        }

        return $goods;
    }

}