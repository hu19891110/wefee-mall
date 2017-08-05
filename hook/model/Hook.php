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
                'title' => '微信商城',
                'href' => aurl('WefeeMall'),
            ]
        ];
        $menus = array_merge($menus, $our);
        config(['WEFEE_SECOND_MENUS' => $menus]);
    }

}