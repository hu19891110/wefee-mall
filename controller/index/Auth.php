<?php namespace addons\wefeemall\controller\index;

use think\Validate;
use think\helper\Hash;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallUsers;
use addons\wefeemall\traits\VerifyCodeCheck;

class Auth extends Base
{
    use VerifyCodeCheck;

    public function login()
    {
        $title = '登录';

        return view(VIEW_PATH . '/index/auth/login.html', compact('title'));
    }

    public function postLogin()
    {
        $request = request();

        $this->checkVerifyCode();

        $data = [
            'username' => $request->param('username'),
            'password' => $request->param('password'),
        ];

        if (! AuthManage::attempt($data, true)) {
            $this->error('登录失败');
        }

        $this->success('登录成功', aurl('wefeemall/index.member'));
    }

    public function logout()
    {
        AuthManage::logout();

        $this->success('注销成功', aurl('wefeemall/index.auth/login'));
    }

    public function register()
    {
        $title = '注册';

        return view(VIEW_PATH . '/index/auth/register.html', compact('title'));
    }

    public function postRegister()
    {
        $request = request();

        $this->checkVerifyCode();

        $data = $request->only([
            'nickname', 'username', 'password', 'password_confirm',
        ]);

        $validator = validate('addons\wefeemall\validators\Member');

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        $member = new MallUsers;
        $member->create([
            'nickname' => $data['nickname'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        $this->success('注册成功，请登录。', aurl('wefeemall/index.auth/login'));
    }

    public function findPass()
    {
        $title = '找回密码';

        return view(VIEW_PATH . '/index/auth/password.html', compact('title'));
    }

    public function postFindPass()
    {
        $request = request();

        $this->checkVerifyCode();

        $data = $request->only([
            'mobile', 'password', 'password_confirm',
        ]);

        $validator = new Validate([
            'password|新密码' => 'require|min:6|max:16|confirm',
        ],[
            'password.require' => '请输入密码',
            'password.min' => '密码长度在6-16个字符',
            'password.max' => '密码长度在6-16个字符',
            'password.confirm' => '两次输入密码不一致',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        $user = MallUsers::get(['username' => $data['mobile']]);

        if (! $user) {
            $this->error('用户不存在');
        }

       $user->password = Hash::make($data['password']);
        $user->save();

        $this->success('密码重置成功，请登录。', aurl('wefeemall/index.auth/login'));
    }

}