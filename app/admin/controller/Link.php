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
use app\admin\logic\Link as Linklogic;

class Link extends Base
{
    /**
     * 获取链接列表
     *
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Linklogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 添加链接
     *
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Linklogic::addlogic(Request()::post()));
        }
    }

    /**
     * 更新链接状态
     *
     * @return void
     */
    public function status()
    {
        if (isPost()) {
            json(Linklogic::statuslogic(Request()::post()));
        }
    }

    /**
     * 获取链接更新信息
     *
     * @return void
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Linklogic::getUpdateInfoslogic(Request()::post('id')));
        }
    }

    /**
     * 删除链接
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Linklogic::deletelogic(Request()::post('id')));
        }
    }

    /**
     * 更新链接
     *
     * @return void
     */
    public function update()
    {
        if (isPost()) {
            json(Linklogic::updatelogic(Request()::post()));
        }
    }
}