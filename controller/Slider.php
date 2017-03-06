<?php namespace addons\wefeemall\controller;

use think\Validate;
use app\common\controller\Base;
use addons\wefeemall\model\MallSliders;

class Slider extends Base
{

    public function index()
    {
        $sliders = MallSliders::order('slider_sort', 'asc')->select();

        $title = '幻灯片列表';

        return view(VIEW_PATH . '/admin/slider/index.html', compact('title', 'sliders'));
    }

    public function add()
    {
        $title = '添加幻灯片';

        return view(VIEW_PATH . '/admin/slider/add.html', compact('title'));
    }

    public function create()
    {
        $data = $this->validator();

        MallSliders::create($data);

        $this->success('操作成功', aurl('wefeemall/slider'));
    }

    public function edit()
    {
        $slider = $this->getSlider();

        $title = '编辑幻灯片';

        return view(VIEW_PATH . '/admin/slider/edit.html', compact('title', 'slider'));
    }

    public function update()
    {
        $slider = $this->getSlider();

        $data = $this->validator();

        $slider->save($data);

        $this->success('操作成功', aurl('wefeemall/slider'));
    }

    protected function validator()
    {
        $data = request()->only([
            'slider_sort', 'slider_title', 'slider_photo',
            'slider_url', 'slider_status',
        ]);

        $validator = new Validate([
            'slider_sort|排序' => 'require',
            'slider_photo|幻灯片图片' => 'require',
        ]);

        if (! $validator->check($data)) {
            $this->error($validator->getError());
        }

        return $data;
    }

    public function delete()
    {
        $slider = $this->getSlider();

        $slider->delete();

        $this->success('操作成功');
    }

    protected function getSlider()
    {
        $slider = MallSliders::get(request()->param('slider_id'));

        if (! $slider) {
            $this->error('幻灯片不存在');
        }

        return $slider;
    }

}