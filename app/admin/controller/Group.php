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
use app\admin\logic\Group as Grouplogic;
/**
 * 用户组控制器
 * @author zhaosong
 */
class Group extends Base
{
    /**
     * 获取用户组列表
     *
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Grouplogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 获取用户组字段列表
     *
     * @return void
     */
    public function getFieldList()
    {
        if (isPost()) {
            json(Grouplogic::getFieldListlogic());
        }
    }

    /**
     * 用户组授权
     *
     * @return void
     */
    public function auth()
    {
        if (isPost()) {
            json(Grouplogic::authlogic(Request()::post()));
        }
    }

    /**
     * 添加用户组
     *
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Grouplogic::addlogic(Request()::post()));
        }
    }

    /**
     * 获取用户组更新信息
     *
     * @return void
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Grouplogic::getUpdateInfoslogic(Request()::post('groupid')));
        }
    }

    /**
     * 更新用户组
     *
     * @return void
     */
    public function update()
    {
        if (isPost()) {
            json(Grouplogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 删除用户组
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Grouplogic::deletelogic(Request()::post('groupid')));
        }
    }
    
    /**
     * 删除用户组权限
     *
     * @return void
     */
    public function del()
    {
        if (isPost()) {
            json(Grouplogic::dellogic(Request()::post('id')));
        }
    }
}