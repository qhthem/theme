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
namespace app\admin\controller;
use app\admin\logic\Login as L;

/**
 * 后台登录控制器
 */

class Login
{
    /**
     * 显示登录页面
     */
    public function index()
    {
        if(Session()::get('admininfo')){
            showmsg("登录成功!", '/'.admin_map('admin'));
        }
        
        include view('admin','login');
    }

    /**
     * 用户登录方法
     *
     * @return void
     */
    public function login()
    {      
        if(isPost()){
            $params = L::loginlogic(Request()::post());
            if ($params){
                json($params);
            }
        }
    }

    /**
     * 处理退出请求
     */
    public function logout()
    {
       L::logoutlogic();
    }
}