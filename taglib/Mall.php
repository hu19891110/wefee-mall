<?php namespace addons\wefeemall\taglib;

use think\template\TagLib;

class Mall extends TagLib
{

    protected $tags = [
        'goods' => ['attr' => 'category_id,order,limit', 'close' => 1],
        'slider' => ['attr' => '', 'close' => 1],
        'categories' => ['attr' => 'fid,level,limit', 'close' => 1],
    ];

    public function tagGoods($tag, $content)
    {
        $where = '$where=[];';
        if (isset($tag['category_id'])) {
            $category_id = $tag['category_id'];
            $where .= '$where["category_id"] = '.$category_id.';';
        }

        $order = isset($tag['order']) ? $tag['order'] : 'created_at desc';
        $limit = isset($tag['limit']) ? (int) $tag['limit'] : 6;

        $php = <<<php
<?php
{$where}
\$goods = \addons\wefeemall\model\MallGoods::where(\$where)->order("{$order}")->limit({$limit})->select();
foreach(\$goods as \$gi => \$item) {
?>
php;
        $php .= $content;
        $php .= "<?php } ?>";

        return $php;

    }

    /**
     * 幻灯片标签，内置 $slider 变量
     */
    public function tagSlider($tag, $content)
    {
        $php = <<<php
<?php
\$sliders = \addons\wefeemall\model\MallSliders::where('slider_status', 1)->order('slider_sort', 'asc')->select();
foreach(\$sliders as \$slider) {
?>
php;
        $php .= $content;
        $php .= "<?php } ?>";

        return $php;
    }

    public function tagCategories($tag, $content)
    {
        $where = '$where=[];';
        if (isset($tag['fid'])) {
            $fid = $tag['fid'];
            $where .= '$where["fid"]='.$fid.';';
        } else {
            $level = isset($tag['level']) ? (int) $tag['level'] : 1;
            if ($level == 1) {
                $where .= '$where["fid"]=["=", '.$level.'];';
            } else {
                $where .= '$where["fid"]=["<>", '.$level.'];';
            }
        }

        $limit = isset($tag['limit']) ? (int) $tag['limit'] : 100;

        $php = <<<php
<?php
{$where}
\$categories = \addons\wefeemall\model\MallCategories::where(\$where)->order('category_sort', 'asc')->limit({$limit})->select();
foreach (\$categories as \$ci => \$category) {
?>
php;
        $php .= $content;
        $php .= '<?php } ?>';

        return $php;
    }

}