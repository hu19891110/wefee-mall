<?php namespace addons\wefeemall\controller\api;

use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use think\Log;
use think\Validate;
use addons\wefeemall\controller\index\Base;
use addons\wefeemall\traits\VerifyCodeCheck;


class MobileCaptcha extends Base
{

    use VerifyCodeCheck;

    protected $client = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->client = new Client(new App([
            'app_key' => get_addon_config('wefeemall', 'sms_app_id'),
            'app_secret' => get_addon_config('wefeemall', 'sms_app_secret'),
        ]));
    }

    public function send()
    {
        $this->checkVerifyCode();

        $this->validator();

        $code = mt_rand(1000, 9999);
        session('mobile_verify_code', [
            'mobile' => request()->param('mobile'),
            'code'   => $code,
        ]);

        /** 发送验证码 */
        $req = new AlibabaAliqinFcSmsNumSend;
        $req->setSmsFreeSignName(get_addon_config('wefeemall', 'sms_sign_name'))
            ->setRecNum(request()->param('mobile'))
            ->setSmsParam(['code' => $code])
            ->setSmsTemplateCode(get_addon_config('wefeemall', 'sms_template_' . request()->param('type') . '_id'));

        $response = $this->client->execute($req);

        /** 记录日志 */
        Log::info($response);

        $this->success('发送成功');
    }

    protected function validator()
    {
        $data = request()->only(['mobile']);

        $validator = new Validate([
            'mobile' => 'require|mobile',
        ], [
            'mobile.require' => '请输入手机号',
            'mobile.mobile'  => '请输入有效手机号',
        ]);
        $validator->extend('mobile', function ($value) {
            return preg_match('/^(((13[0-9]{1})|(15[0-35-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/', $value) === 1 ? true : false;
        });

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }
    }

}