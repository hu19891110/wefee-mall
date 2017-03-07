<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallCategories;
use addons\wefeemall\model\MallCoupons;
use addons\wefeemall\model\MallCouponUsers;
use addons\wefeemall\model\MallGoods;
use app\common\controller\Base;
use think\Validate;

class Coupon extends Base
{

    public function index()
    {
        $coupons = MallCoupons::where('is_delete', -1)->order('coupon_sort', 'asc')->paginate(20);

        $title = '优惠券管理';

        return view(VIEW_PATH . '/admin/coupon/index.html', compact('title', 'coupons'));
    }

    public function add()
    {
        $title = '添加优惠券';

        return view(VIEW_PATH . '/admin/coupon/add.html', compact('title'));
    }

    public function create()
    {
        $data = $this->validator();

        /** 创建优惠券 */
        $coupon = new MallCoupons;
        $coupon->save([
            'coupon_type'   => $data['coupon_type'],
            'coupon_sort'   => $data['coupon_sort'],
            'coupon_name'   => $data['coupon_name'],
            'coupon_cost'   => $data['coupon_cost'],
            'coupon_inf'    => $data['coupon_inf'],
            'coupon_num'    => $data['coupon_num'],
            'coupon_status' => $data['coupon_status'],
            'start_at'      => $data['start_at'],
            'end_at'        => $data['end_at'],
        ]);

        /** 生成子券 */
        $this->genChildrenCoupon($data, $coupon);

        /** 关联分类，商品 */
        $this->associateCategory($data, $coupon);
        $this->associateGoods($data, $coupon);

        $this->success('操作成功');
    }

    protected function validator()
    {
        $data = request()->only([
            'coupon_type', 'coupon_sort', 'coupon_name', 'coupon_cost', 'coupon_inf',
            'coupon_num', 'coupon_status', 'start_at', 'end_at',
        ]);
        $data['category'] = request()->post('category/a');
        $data['goods'] = request()->post('goods/a');

        $validator = new Validate([
            'coupon_sort|排序'      => 'require',
            'coupon_type|类型'      => 'require',
            'coupon_name|优惠券名称' => 'require',
            'coupon_cost|价格'      => 'require',
            'coupon_num|发行数量'    => 'require',
            'start_at|开始时间'      => 'require',
            'end_at|结束时间'        => 'require',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function edit()
    {
        $coupon = $this->getCoupon();

        $title = '编辑优惠券';

        return view(VIEW_PATH . '/admin/coupon/edit.html', compact('title', 'coupon'));
    }

    public function update()
    {
        $coupon = $this->getCoupon();

        $data = $this->validator();

        if ($coupon->coupon_num != $data['coupon_num']) {
            /** 继续增发 */
            $this->genChildrenCoupon($data, $coupon);
        }

        $coupon->save([
            'coupon_type'   => $data['coupon_type'],
            'coupon_sort'   => $data['coupon_sort'],
            'coupon_name'   => $data['coupon_name'],
            'coupon_cost'   => $data['coupon_cost'],
            'coupon_inf'    => $data['coupon_inf'],
            'coupon_num'    => $data['coupon_num'],
            'coupon_status' => $data['coupon_status'],
            'start_at'      => $data['start_at'],
            'end_at'        => $data['end_at'],
        ]);

        $this->associateCategory($data, $coupon);
        $this->associateGoods($data, $coupon);

        $this->success('操作成功');
    }

    protected function genChildrenCoupon(array $data, MallCoupons $coupon)
    {
        $tmp = [];
        for ($i = 0; $i < $data['coupon_num']; $i++) {
            $tmp[] = ['coupon_id' => $coupon->id, 'user_id' => 0];
        }
        $couponUser = new MallCouponUsers;
        $couponUser->saveAll($tmp);
        unset($tmp);
    }

    protected function associateCategory(array $data, MallCoupons $coupon)
    {
        if (! empty($data['category'])) {
            foreach ($coupon->categories as $item) {
                if (! in_array($item->id, $data['category'])) {
                    $coupon->categories()->detach($item->id);
                }
            }

            foreach ($data['category'] as $item) {
                $category = MallCategories::get($item);
                if ($category && ! $coupon->categories()->where(['category_id' => $item])->find()) {
                    $coupon->categories()->save($category);
                }
            }
        } else {
            $coupon->categories()->detach();
        }
    }

    protected function associateGoods(array $data, MallCoupons $coupon)
    {
        if (! empty($data['goods'])) {
            foreach ($coupon->goods as $item) {
                if (! in_array($item->id, $data['goods'])) {
                    $coupon->goods()->detach($item->id);
                }
            }

            foreach ($data['goods'] as $item) {
                $goods = MallGoods::get($item);
                if ($goods && ! $coupon->goods()->where(['goods_id' => $item])->find()) {
                    $coupon->goods()->save($goods);
                }
            }
        } else {
            $coupon->goods()->detach();
        }
    }

    public function delete()
    {
        $coupon = $this->getCoupon();

        $coupon->save([
            'coupon_status' => -1,
            'is_delete' => 1,
        ]);
        /** 子券 */
        $coupon->children()->update(['status' => -1]);

        $this->success('操作成功');
    }

    protected function getCoupon()
    {
        $coupon = MallCoupons::get(request()->param('coupon_id'));

        if (! $coupon) {
            $this->error('优惠券不存在');
        }

        return $coupon;
    }

}