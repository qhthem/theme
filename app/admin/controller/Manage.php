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
use app\admin\logic\Manage as Managelogic;

class Manage extends Base
{
    /**
     * 管理列表
     *
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Managelogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 添加管理员
     *
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Managelogic::addlogic(Request()::post()));
        }
    }

    /**
     * 获取字段列表
     *
     * @return void
     */
    public function getFieldList()
    {
        if (isPost()) {
            json(Managelogic::getFieldListlogic());
        }
    }

    /**
     * 更新管理员信息
     *
     * @return void
     */
    public function update()
    {
        if (isPost()) {
            json(Managelogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 获取更新信息
     *
     * @return void
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Managelogic::getUpdateInfoslogic(Request()::post('adminid')));
        }
    }

    /**
     * 删除管理员
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Managelogic::deletelogic(Request()::post('adminid')));
        }
    }
}