<?php
namespace addons\wefeemall\model;

use think\Model;

class MallWechatUser extends Model
{

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

}