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
        return get_addon_config('wefeemall', $key);
    }
}

 if (! function_exists('mall_url')) {
    /**
     * 获取URL
     * @param string $dir 结构
     * @return string
     */
     function mall_url($dir)
    {
        return aurl('wefeemall/' . ltrim($dir, '/'));
    }
 }
