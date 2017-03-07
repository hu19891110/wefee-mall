<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallFeedback;
use addons\wefeemall\traits\VerifyCodeCheck;
use addons\wefeemall\traits\LoginCheck;
use think\Validate;

class Service extends Base
{
    use VerifyCodeCheck,LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function feedback()
    {
        $title = '意见反馈';

        return view(VIEW_PATH . '/index/service/feedback.html', compact('title'));
    }

    public function postFeedback()
    {
        $this->checkVerifyCode();

        $data = $this->feedbackValidator();

        $feedback = new MallFeedback($data);
        $feedback->save();

        $this->success('感谢您的反馈！');
    }

    protected function feedbackValidator()
    {
        $data = request()->only([
            'feedback_type', 'feedback_content',
        ]);
        $data['user_id'] = AuthManage::id();

        $validator = new Validate([
            'feedback_type|问题类型' => 'require',
            'feedback_content|反馈内容' => 'require|min:15|max:255',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

}