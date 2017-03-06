<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallUserAddress;
use addons\wefeemall\traits\LoginCheck;
use think\Validate;

class Member extends Base
{
    use LoginCheck;

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

        $this->success('添加成功', aurl('wefeemall/index.member/myAddress'));
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

        $this->success('操作成功', aurl('wefeemall/index.member/myAddress'));
    }

}