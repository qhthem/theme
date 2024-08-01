<?php
// +----------------------------------------------------------------------
// | QHPHP [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 https://www.astrocms.cn/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace app\member\controller;
use app\member\logic\Login as Loginlogic;
use app\member\logic\Index as Indexlogic;

/**
 * Index 类
 *
 * @author zhaosong
 */
class Index
{
    /**
     * 首页页面
     */
    public function index(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->indexlogic();
        }
    }

    /**
     * 成长页面
     */
    public function growth(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->growthlogic();
        }
    }

    /**
     * 账户页面
     */
    public function account(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->accountlogic();
        }
    }

    /**
     * 发布页面
     */
    public function post(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->postlogic();
        }
    }

    /**
     * 设置页面
     */
    public function settings(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->settingslogic();
        }
    }

    /**
     * 订单页面
     */
    public function order(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->orderlogic();
        }
    }

    /**
     * VIP 页面
     */
    public function vip(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->viplogic();
        }
    }

    /**
     * 写作页面
     */
    public function write(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->writelogic();
        }
    }
    

    /**
     * 绑定邮箱
     */
    public function bind_mail(): void
    {
        if (Request()::isGet()) {
            (new Indexlogic())->bind_maillogic();
        }
    }    
    

    /**
     * 登录页面
     */
    public function login(): void
    {
        if (Request()::isPost()) {
            json(Loginlogic::loginlogic(Request()::post()));
        }
    }
    
    
    /**
     * qq登录
     */
    public function qq_login(): void
    {
        if (Request()::isGet()) {
            Loginlogic::qq_loginlogic();
        }
    }

    /**
     * 注册页面
     */
    public function register(): void
    {
        if (Request()::isPost()) {
            json(Loginlogic::registerlogic(Request()::post()));
        }
    }

    /**
     * 退出页面
     */
    public function exit(): void
    {
        if (Request()::isGet()) {
            Loginlogic::exitlogic();
        }
    }
    
    
    /**
     * 获取用户信息接口
     *
     * @author zhaosong
     */
    public function get_userinfo()
    {
        if (Request()::isPost()) {
            json(Loginlogic::get_userinfos(Request()::post()));
        }
    }     
}