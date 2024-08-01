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
namespace app\index\controller;
use app\index\logic\Index as Indexlogic;

/**
 * 首页控制器
 *
 * @author zhaosong
 */
class Index
{
    /**
     * 首页
     *
     * @return void
     */
    public function index():void
    {
        if (Request()::isGet()) {
            Indexlogic::indexlogic();
        }
    }

    /**
     * 列表页
     *
     * @return void
     */
    public function lists():void
    {
        if (Request()::isGet()) {
            Indexlogic::listslogic();
        }
    }

    /**
     * 详情页
     *
     * @return void
     */
    public function show():void
    {
        if (Request()::isGet()) {
            Indexlogic::showlogic();
        }
    }
}