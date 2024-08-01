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
use app\admin\logic\Content as Contentlogic;
/**
 * 内容管理类
 * @author zhaosong
 */
class Content extends Base
{
    /**
     * 获取内容列表
     * @return json
     */
    public function index()
    {
        if (isPost()) {
            json(Contentlogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 添加内容
     * @return json
     */
    public function add()
    {
        if (isPost()) {
            json(Contentlogic::addlogic(Request()::post()));
        }
    }

    /**
     * 获取字段列表
     * @return json
     */
    public function getFieldList()
    {
        if (isPost()) {
            json(Contentlogic::getFieldListlogic());
        }
    }

    /**
     * 更新内容状态
     * @return json
     */
    public function status()
    {
        if (isPost()) {
            json(Contentlogic::statuslogic(Request()::post()));
        }
    }

    /**
     * 获取更新信息
     * @return json
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Contentlogic::getUpdateInfoslogic(Request()::post()));
        }
    }

    /**
     * 更新内容
     * @return json
     */
    public function update()
    {
        if (isPost()) {
            json(Contentlogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 删除内容
     * @return json
     */
    public function delete()
    {
        if (isPost()) {
            json(Contentlogic::deletelogic(Request()::post('id')));
        }
    }

    /**
     * 批量操作
     * @return json
     */
    public function batch()
    {
        if (isPost()) {
            json(Contentlogic::batchlogic(Request()::post()));
        }
    }
}