<?php namespace addons\wefeemall\controller;

use addons\wefeemall\model\MallUsers;
use app\common\controller\Base;
use think\Validate;

class Member extends Base
{

    public function index()
    {
        $where = [];
        $params = [];
        $request = request();
        if ($request->has('key_words') && $request->param('key_words') != '') {
            $where['nickname|username'] = ['LIKE', '%'.$request->param('key_words').'%'];
            $params['key_words'] = $request->param('key_words');
        }

        $users = MallUsers::where($where)->order('created_at', 'desc')->paginate(20, false, ['query' => $params]);

        $title = '会员列表';

        return view(VIEW_PATH . '/admin/member/index.html', compact('title', 'users'));
    }

    public function edit()
    {
        $member = $this->getUser();

        $title = '编辑会员信息';

        return view(VIEW_PATH . '/admin/member/edit.html', compact('title', 'member'));
    }

    public function update()
    {
        $user = $this->getUser();

        $data = request()->only([
            'nickname', 'username', 'avatar', 'credit1', 'credit2',
        ]);

        $validator = new Validate([
            'nickname|呢称' => 'require',
            'username|手机号' => 'require',
            'avatar|头像' => 'require',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        $user->save($data);

        $this->success('操作成功');
    }

    public function ban()
    {
        $user = $this->getUser();

        $user->status = $user->status == 1 ? -1 : 1;
        $user->save();

        $this->success('操作成功');
    }

    public function info()
    {
        $member = $this->getUser();

        $title = '会员详细信息';

        return view(VIEW_PATH . '/admin/member/info.html', compact('title', 'member'));
    }

    protected function getUser()
    {
        $user = MallUsers::get(request()->param('user_id'));

        if (! $user) {
            $this->error('用户不存在');
        }

        return $user;
    }

    public function charts()
    {
        $dataSource = [];
        $startDate = strtotime('-30 days');
        while ($startDate < time()) {
            $count = MallUsers::where('created_at', '>=', date('Y-m-d', $startDate))
                                ->where('created_at', '<', date('Y-m-d', $startDate + 3600*24))
                                ->count();

            $dataSource['label'][] = date('m/d', $startDate);
            $dataSource['data'][]  = $count;

            $startDate += 3600 * 24;
        }

        $title = '会员统计';

        return view(VIEW_PATH . '/admin/member/charts.html', compact('title', 'dataSource'));
    }

}