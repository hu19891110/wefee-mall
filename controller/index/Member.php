<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\model\MallUsers;
use addons\wefeemall\model\MallWechatUser;
use think\Validate;
use think\helper\Hash;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;
use addons\wefeemall\traits\VerifyCodeCheck;
use addons\wefeemall\model\MallUserAddress;
use addons\wefeemall\traits\MobileVerifyCodeCheck;

class Member extends Base
{
    use LoginCheck,VerifyCodeCheck,MobileVerifyCodeCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function index()
    {
        $user = AuthManage::user();

        $title = '会员中心';

        return view(VIEW_PATH . '/index/member/index.html', compact('title', 'user'));
    }

    public function myAddress()
    {
        $user = AuthManage::user();

        $title = '我的地址';

        return view(VIEW_PATH . '/index/member/address/index.html', compact('title', 'user'));
    }

    public function addAddress()
    {
        $title = '添加地址';

        return view(VIEW_PATH . '/index/member/address/add.html', compact('title'));
    }

    public function postCreateAddress()
    {
        $data = $this->createAddressValidator();
        $area = explode(',', $data['area']);
        $data = [
            'user_id'       => AuthManage::id(),
            'true_name'     => $data['true_name'],
            'contact_phone' => $data['contact_phone'],
            'province'      => $area[0],
            'city'          => count($area) == 2 ? $area[0] : $area[1],
            'region'        => $area[count($area) - 1],
            'street'        => $data['street'],
            'is_default'    => isset($data['is_default']) ? 1 : 0,
        ];

        $address = new MallUserAddress($data);
        $address->save();

        if ($data['is_default']) {
            MallUserAddress::where('id', '<>', $address->id)->update(['is_default' => 0]);
        }

        if (request()->param('referer') != '') {
            return redirect(urldecode(request()->param('referer')));
        }

        $this->success('添加成功', mall_url('index.member/myAddress'));
    }

    protected function createAddressValidator()
    {
        $data = request()->only([
            'true_name', 'contact_phone', 'area', 'street', 'is_default',
        ]);

        $validator = new Validate([
            'true_name|联系人'       => 'require',
            'contact_phone|联系电话' => 'require',
            'area|地区'              => 'require',
            'street|详细地址'        => 'require',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function deleteAddress()
    {
        MallUserAddress::get(request()->param('id'))->delete();

        $this->success('操作成功', mall_url('index.member/myAddress'));
    }

    public function safe()
    {
        $user = AuthManage::user();

        $title = '账号安全';

        return view(VIEW_PATH . '/index/member/safe/index.html', compact('title', 'user'));
    }

    public function changePass()
    {
        $title = '修改密码';

        return view(VIEW_PATH . '/index/member/safe/change_pass.html', compact('title'));
    }

    public function postChangePass()
    {
        $this->checkVerifyCode();

        $data = $this->changePassValidator();

        $user = AuthManage::user();

        if (! Hash::check($data['old_password'], $user->password)) {
            $this->error('原密码不正确', aurl('wefeemall/index.member/changePass'));
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        $this->success('密码修改成功');
    }

    protected function changePassValidator()
    {
        $data = request()->only([
            'old_password', 'new_password', 'new_password_confirm',
        ]);

        $validator = new Validate([
            'new_password|新密码' => 'require|min:6|max:16|confirm',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function changeMobile()
    {
        $user = AuthManage::user();

        $title = '更换手机号';

        return view(VIEW_PATH . '/index/member/safe/change_mobile.html', compact('title', 'user'));
    }

    public function postChangeMobile()
    {
        $this->checkVerifyCode();

        $this->mobileVerifyCodeCheck();

        $data = $this->changeMobileValidator();

        $user = AuthManage::user();

        if (! Hash::check($data['password'], $user->password)) {
            $this->error('密码不正确', mall_url('index.member/changeMobile'));
        }

        $user->mobile = $data['username'];
        $user->save();

        $this->success('手机号更换成功');
    }

    protected function changeMobileValidator()
    {
        $data = request()->only([
            'username', 'password',
        ]);

        $validator = new Validate([
            'username|手机号' => 'require|mobile|unique:mall_users',
        ]);
        $validator->extend('mobile', function ($value) {
            return preg_match('/^(((13[0-9]{1})|(15[0-35-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/', $value) === 1 ? true : false;
        });

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function order()
    {
        $orders = AuthManage::user()->orders()->order('created_at', 'desc')->paginate(5);

        $title = '我的订单';

        return view(VIEW_PATH . '/index/member/order/list.html', compact('title', 'orders'));
    }

    public function getOrderInfo()
    {
        $order = AuthManage::user()->orders()->where('id', request()->param('id'))->find();

        ! $order && $this->error('订单不存在');

        $title = '订单详情';

        return view(VIEW_PATH . '/index/member/order/info.html', compact('title', 'order'));
    }

    public function getOrderDel()
    {
        $order = AuthManage::user()->orders()->where('id', request()->param('id'))->find();

        ! $order && $this->error('订单不存在');

        $order['order_status'] < 9 && $this->error('该订单暂未发货');

        $del = get_order_del($order);

        $title = '物流详情';

        return view(VIEW_PATH . '/index/member/order/del.html', compact('title', 'del', 'order'));
    }

    public function wechat()
    {
        if (! is_wechat()) {
            $this->error('请通过微信访问');
        }
        $wechatUser = wechat_web_auth();
        /** 检测是否已经绑定账号 */
        $wechat = AuthManage::user()->wechat()->where('openid', $wechatUser->getId())->find();
        if ($wechat) {
            $this->error('当前账号已经绑定过微信号啦！');
        }
        /** 需要绑定 */
        $data = [
            'user_id' => AuthManage::id(),
            'openid' => $wechatUser->getId(),
            'nickname' => emoji_filter($wechatUser->getNickname()),
            'avatar' => $wechatUser->getAvatar(),
        ];
        $result = MallWechatUser::create($data);
        if ($result) {
            MallUsers::where('id', AuthManage::id())->update(['avatar' => $wechatUser->getAvatar()]);
            $this->success('绑定成功');
        }
        $this->error('绑定失败');
    }

}