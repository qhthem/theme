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
use app\admin\logic\Base as B;
use app\admin\logic\Index as Indexlogic;
/**
 * 主页控制器
 */
class Index extends Base
{
    /**
     * 显示主页
     *
     * @return void
     */
    public function index()
    {
        $admin = admin_map('admin');
        $token = B::RequestTokenlogic();
        
        include view('admin','index');
    }
    
    /**
     * 获取菜单数据并返回 JSON 响应
     *
     * @return void
     */
    public function menu()
    {
        if (isPost()){
            json(Indexlogic::menulogic());
        }
    }

    /**
     * 根据传入的参数生成面包屑导航并返回 JSON 响应
     *
     * @return void
     */
    public function Breadcrumb()
    {
        if (isPost()){
            json(Indexlogic::Breadcrumblogic(Request()::post()));
        }
    }
    
}