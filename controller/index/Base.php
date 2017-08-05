<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallWechatUser;
use think\Controller;

class Base extends Controller
{

    public function _initialize()
    {
        parent::_initialize();

        /** 初始化错误，失败界面 */
        config('dispatch_success_tmpl', VIEW_PATH . '/index/common/success.html');
        config('dispatch_error_tmpl', VIEW_PATH . '/index/common/error.html');

        $this->autoLogin();
    }

    // 微信自动登陆
    protected function autoLogin()
    {
        /** 微信授权 */
        $wechatUser = wechat_web_auth();
        if ($wechatUser) {
            $user = MallWechatUser::where('openid', $wechatUser->getId())->find();
            if ($user) {
                AuthManage::loginByUserId($user->user_id);
            }
        }
    }

}