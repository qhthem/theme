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
use app\admin\logic\Role as Rolelogic;

class Role extends Base
{
    /**
     * 获取角色列表
     */
    public function index()
    {
        if (isPost()) {
            json(Rolelogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 添加角色
     */
    public function add()
    {
        if (isPost()) {
            json(Rolelogic::addlogic(Request()::post()));
        }
    }

    /**
     * 获取角色更新信息
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Rolelogic::getUpdateInfoslogic(Request()::post('roleid')));
        }
    }

    /**
     * 更新角色信息
     */
    public function update()
    {
        if (isPost()) {
            json(Rolelogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 获取角色权限
     */
    public function priv()
    {
        if (isPost()) {
            json(Rolelogic::privlogic(Request()::post('roleid')));
        }
    }

    /**
     * 获取字段列表
     */
    public function getFieldList()
    {
        if (isPost()) {
            json(Rolelogic::getFieldListlogic());
        }
    }

    /**
     * 角色权限设置
     */
    public function role_priv()
    {
        if (isPost()) {
            json(Rolelogic::role_privlogic(Request()::post()));
        }
    }

    /**
     * 删除角色
     */
    public function delete()
    {
        if (isPost()) {
            json(Rolelogic::deletelogic(Request()::post('roleid')));
        }
    }
}