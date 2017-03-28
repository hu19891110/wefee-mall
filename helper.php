 <?php
/**
 * 自定义函数库
 * @author 轻色年华 <616896861@qq.com> 
 */

if (! function_exists('mall_config')) {
    /**
     * 获取商城配置
     * @param string $key 键值
     * @return mixed 
     */
    function mall_config($key = '')
    {
        return get_addon_config(MALL_SIGN, $key);
    }
}

 if (! function_exists('mall_url')) {
    /**
     * 获取URL
     * @param string $dir 结构
     * @param array $params 参数
     * @return string
     */
     function mall_url($dir, $params = [])
    {
        return aurl(MALL_SIGN . '/' . ltrim($dir, '/'), $params);
    }
 }

 if (! function_exists('get_order_status_text')) {
    function get_order_status_text($status)
    {
        $s = '';
        switch ($status) {
            case 1:
                $s = '待付款';
                break;
            case 5:
                $s = '待发货';
                break;
            case 9:
                $s = '已发货';
                break;
            case 13:
                $s = '已收货';
                break;
            case 17:
                $s = '已完成';
                break;
        }
        return $s;
    }
 }

 if (! function_exists('get_origin_order_goods')) {
    function get_origin_order_goods(\addons\wefeemall\model\MallOrders $order, \addons\wefeemall\model\MallGoods $goods)
    {
        $data = \addons\wefeemall\model\MallOrderGoods::where([
            'order_id' => $order->id,
            'goods_id' => $goods->id,
        ])->find();

        if (! $data) {
            return null;
        }

        return $data;
    }
 }

 if (! function_exists('get_order_del')) {
    function get_order_del(\addons\wefeemall\model\MallOrders $order)
    {
        $address = $order->address;
        $box = [
            '顺丰' => 'sf',
            '申通' => 'sto',
            '圆通' => 'yt',
            '韵达' => 'yd',
            '天天' => 'tt',
            'EMS' => 'ems',
            '中通' => 'zto',
            '汇通' => 'ht',
        ];
        $response = @file_get_contents('http://v.juhe.cn/exp/index?com='.$box[$address->del_company].'&key='.mall_config('juhe_secret').'&no='.$address->del_number);
        $arr = json_decode($response, true);
        if (! $arr['resultcode']) {
            return null;
        }
        return $arr['result']['list'];
    }
 }