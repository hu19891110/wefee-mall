<?php namespace addons\wefeemall\hook\model;


class Hook
{

    public function appInit()
    {
        /** 注册模板标签 */
        $tags = explode(',', config('template.taglib_pre_load'));
        $tags[] = 'addons\wefeemall\taglib\Mall';
        config('template.taglib_pre_load', implode(',', $tags));
    }

    /**
     * 注册Wefee二级菜单
     * @return void
     */
    public function appBegin()
    {
        $menus = config('WEFEE_SECOND_MENUS');
        $menus = $menus?:[];
        $our = [
            [
                'title' => '微菲商城',
                'href' => aurl('WefeeMall'),
            ],
            [
                'title' => '微菲商城配置',
                'href' => url('addons/addons/config', ['addons_sign' => 'WefeeMall']),
            ],
        ];
        $menus = array_merge($menus, $our);
        config(['WEFEE_SECOND_MENUS' => $menus]);
    }

}