<?php namespace addons\wefeemall\transform;

use addons\wefeemall\model\MallUsers;

class UserTransformer
{

    public function transform(MallUsers $user)
    {
        return [
            'id'       => $user->id,
            'nickname' => $user->nickname,
            'username' => str_replace(substr($user->username, 3, 4), '****', $user->username),
            'avatar'   => $user->avatar,
        ];
    }

}