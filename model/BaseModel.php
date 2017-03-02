<?php namespace addons\wefeemall\model;

use think\Model;

class BaseModel extends Model
{

    /** 时间预定义 */
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

}