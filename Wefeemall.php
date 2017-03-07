<?php namespace addons\wefeemall;

use Qsnh\think\Addons\Addons;
use think\Db;

class Wefeemall extends Addons
{

    public function up()
    {
        /** 安装用户表 */
        $this->installTableUsers();

        /** 安装商品分类表 */
        $this->installTableCategories();

        /** 安装商品表 */
        $this->installTableGoods();

        /** 安装商品评论表 */
        $this->installTableGoodsComments();

        /** 安装用户地址表 */
        $this->installTableUserAddress();

        /** 安装幻灯片表 */
        $this->installTableSliders();

        /** 安装订单表 */


        /** 安装反馈表 */
        $this->installTableFeedback();

        /** 安装优惠券 */
        $this->installTableCoupons();
    }

    protected function template()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS '.full_table('mall_users').'(
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `created_at` timestamp,
        `updated_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function installTableUsers()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS `'.full_table('mall_users').'` (
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `nickname` varchar(24) not null comment "呢称",
        `avatar` varchar(255) not null comment "头像",
        `username` varchar(11) not null comment "手机",
        `password` varchar(64) not null comment "密码",
        `credit1` decimal(10,2) unsigned default 0 not null comment "余额",
        `credit2` decimal(10,2) unsigned default 0 not null comment "积分",
        `status` tinyint(1) default 1 not null comment "1正常-1锁定",
        `lock_message` varchar(255) default "" not null comment "锁定原因",
        `created_at` timestamp,
        `updated_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function installTableCategories()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS `'.full_table('mall_categories').'` (
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `fid` int(11) unsigned not null comment "上级ID",
        `category_sort` int(11) not null comment "升序",
        `category_name` varchar(32) not null comment "分类名",
        `category_icon` varchar(255) not null comment "分类图标",
        `category_status` tinyint(1) default 1 not null comment "1正常-1锁定",
        `created_at` timestamp,
        `updated_at` timestamp,
        `deleted_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function installTableGoods()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS `'.full_table('mall_goods').'` (
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `category_id` int(11) unsigned not null comment "分类ID",
        `goods_name` varchar(255) not null comment "商品标题",
        `goods_photos` varchar(2550) not null comment "商品图片",
        `goods_stock` int(11) unsigned default 0 not null comment "商品库存",
        `goods_origin_cost` decimal(10,2) unsigned not null comment "商品原价",
        `goods_now_cost` decimal(10,2) unsigned not null comment "商品现价",
        `goods_status` tinyint(1) default 1 not null comment "商品状态",
        `goods_addition_params` varchar(2550) not null comment "商品附加参数",
        `goods_score` decimal(3,2) default 0 not null comment "商品评分",
        `goods_sales` int(1) default 0 not null comment "销量",
        `goods_description` text not null comment "商品描述",
        `created_at` timestamp,
        `updated_at` timestamp,
        `published_at` timestamp,
        `deleted_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function installTableGoodsComments()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS `'.full_table('mall_goods_comments').'` (
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `goods_id` int(11) unsigned not null comment "商品评论",
        `user_id` int(11) unsigned not null comment "用户ID",
        `comment_content` varchar(255) not null comment "评论内容",
        `comment_photos` varchar(2550) default "" not null comment "评价图片",
        `comment_status` tinyint(1) default 1 not null comment "1正常-1锁定",
        `created_at` timestamp,
        `updated_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function installTableUserAddress()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS `'.full_table('mall_user_address').'` (
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `user_id` int(11) unsigned not null comment "用户ID",
        `true_name` varchar(10) not null comment "收货姓名",
        `contact_phone` varchar(20) not null comment "联系电话",
        `province` varchar(12) not null,
        `city` varchar(24) not null,
        `region` varchar(32) not null,
        `street` varchar(255) not null,
        `is_default` tinyint(1) default -1 not null,
        `created_at` timestamp,
        `updated_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function installTableSliders()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS `'.full_table('mall_sliders').'` (
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `slider_sort` int(11) unsigned not null comment "升序",
        `slider_title` varchar(255) not null,
        `slider_photo` varchar(255) not null,
        `slider_url` varchar(255) not null,
        `slider_status` tinyint(1) default 1 not null comment "1正常-1锁定",
        `created_at` timestamp,
        `updated_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    /**+++++++++++++++++++++++++++ 我是分割线 ----------------------------------------*/

    public function down()
    {
        $this->uninstallTableUsers();

        $this->uninstallTableCategories();

        $this->uninstallTableGoods();

        $this->uninstallTableGoodsComments();

        $this->uninstallTableUserAddress();

        $this->uninstallTableSliders();

        $this->uninstallTableOrders();

        $this->uninstallTableFeedback();

        $this->uninstallTableCoupons();
    }

    protected function uninstallTableUsers()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_users').';');
    }

    protected function uninstallTableCategories()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_categories').';');
    }

    protected function uninstallTableGoods()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_goods').';');
    }

    protected function uninstallTableGoodsComments()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_goods_comments').';');
    }

    protected function uninstallTableSliders()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_sliders').';');
    }

    protected function uninstallTableUserAddress()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_user_address').';');
    }

    protected function uninstallTableOrders()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_orders').';');
    }

    public function upgrade()
    {
        $this->installTableFeedback();

        $this->installTableCoupons();
    }

    protected function installTableFeedback()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS '.full_table('mall_feedback').'(
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `user_id` int(11) unsigned not null,
        `feedback_type` varchar(32) not null comment "反馈问题类型",
        `feedback_content` varchar(255) not null comment "反馈内容",
        `created_at` timestamp,
        `updated_at` timestamp,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function uninstallTableFeedback()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_feedback').';');
    }

    protected function installTableCoupons()
    {
        Db::execute('
        CREATE TABLE IF NOT EXISTS '.full_table('mall_coupons').'(
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `coupon_sort` int(11) unsigned not null comment "升序",
        `coupon_type` varchar(24) default "" not null comment "满减,直减,免邮,折扣",
        `coupon_name` varchar(64) default "" not null comment "优惠券名称",
        `coupon_cost` decimal(10,2) unsigned default 0 not null comment "价格",
        `coupon_inf` decimal(10,2) unsigned default 0 not null comment "下确界",
        `coupon_num` int(11) unsigned not null comment "发放数量",
        `coupon_status` smallint(1) default 1 comment "1正常-1失效",
        `created_at` timestamp,
        `updated_at` timestamp,
        `start_at` timestamp,
        `end_at` timestamp,
        `is_delete` tinyint(1) default -1 not null,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');

        Db::execute('
        CREATE TABLE IF NOT EXISTS '.full_table('mall_coupon_users').'(
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `coupon_id` int(11) unsigned not null,
        `user_id` int(11) unsigned not null,
        `status` tinyint(1) default 1 not null comment "1正常-1已使用3过期",
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');

        Db::execute('
        CREATE TABLE IF NOT EXISTS '.full_table('mall_coupon_category').'(
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `coupon_id` int(11) unsigned not null,
        `category_id` int(11) unsigned not null,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');

        Db::execute('
        CREATE TABLE IF NOT EXISTS '.full_table('mall_coupon_goods').'(
        `id` int(11) unsigned not null AUTO_INCREMENT,
        `coupon_id` int(11) unsigned not null,
        `goods_id` int(11) unsigned not null,
        PRIMARY KEY(`id`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ');
    }

    protected function uninstallTableCoupons()
    {
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_coupons').';');
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_coupon_category').';');
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_coupon_goods').';');
        Db::execute('DROP TABLE IF EXISTS '.full_table('mall_coupon_users').';');
    }

}