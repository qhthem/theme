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
use app\admin\logic\Member as Memberlogic;

/**
 * Author: zhaosong
 * ClassName: Member
 * Function: 用户管理相关操作
 */
class Member extends Base
{
    /**
     * Author: zhaosong
     * Method: index
     * Function: 获取用户列表
     */
    public function index()
    {
        if (isPost()) {
            json(Memberlogic::indexlogic(Request()::post()));
        }
    }

    /**
     * Author: zhaosong
     * Method: add
     * Function: 添加用户
     */
    public function add()
    {
        if (isPost()) {
            json(Memberlogic::addlogic(Request()::post()));
        }
    }

    /**
     * Author: zhaosong
     * Method: getUpdateInfos
     * Function: 获取用户更新信息
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Memberlogic::getUpdateInfoslogic(Request()::post('userid')));
        }
    }

    /**
     * Author: zhaosong
     * Method: status
     * Function: 更新用户状态
     */
    public function status()
    {
        if (isPost()) {
            json(Memberlogic::statuslogic(Request()::post()));
        }
    }

    /**
     * Author: zhaosong
     * Method: update
     * Function: 更新用户信息
     */
    public function update()
    {
        if (isPost()) {
            json(Memberlogic::updatelogic(Request()::post()));
        }
    }

    /**
     * Author: zhaosong
     * Method: delete
     * Function: 删除用户
     */
    public function delete()
    {
        if (isPost()) {
            json(Memberlogic::deletelogic(Request()::post('userid')));
        }
    }
}