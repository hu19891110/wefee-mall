<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallFeedback;
use app\common\controller\Base;

class Feedback extends Base
{

    public function index()
    {
        $feedback = MallFeedback::order('created_at', 'desc')->paginate(20);

        $title = '意见反馈';

        return view(VIEW_PATH . '/admin/feedback/index.html', compact('title', 'feedback'));
    }

    public function clearOld()
    {
        $where['created_at'] = ['<', date('Y-m-d', strtotime('-3 month'))];

        MallFeedback::destroy($where);

        $this->success('操作成功');
    }
}