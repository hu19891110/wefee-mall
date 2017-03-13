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

}