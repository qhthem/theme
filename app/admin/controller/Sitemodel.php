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
use app\admin\logic\Sitemodel as Sitemodellogic;

class Sitemodel extends Base
{
    /**
     * 作者: zhaosong
     * 功能: 获取模型列表
     * @return \response\Json
     */
    public function index()
    {
        if (isPost()) {
            json(Sitemodellogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 作者: zhaosong
     * 功能: 添加模型
     * @return \response\Json
     */
    public function add()
    {
        if (isPost()) {
            json(Sitemodellogic::addlogic(Request()::post()));
        }
    }

    /**
     * 作者: zhaosong
     * 功能: 更新模型
     * @return \response\Json
     */
    public function update()
    {
        if (isPost()) {
            json(Sitemodellogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 作者: zhaosong
     * 功能: 获取更新模型信息
     * @return \response\Json
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Sitemodellogic::getUpdateInfoslogic(Request()::post('modelid')));
        }
    }

    /**
     * 作者: zhaosong
     * 功能: 更新模型状态
     * @return \response\Json
     */
    public function status()
    {
        if (isPost()) {
            json(Sitemodellogic::statuslogic(Request()::post()));
        }
    }

    /**
     * 作者: zhaosong
     * 功能: 删除模型
     * @return \response\Json
     */
    public function delete()
    {
        if (isPost()) {
            json(Sitemodellogic::deletelogic(Request()::post('modelid')));
        }
    }
}