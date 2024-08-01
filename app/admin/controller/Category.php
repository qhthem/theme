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
use app\admin\logic\Category as Categorylogic;

class Category extends Base
{
    /**
     * 获取分类列表。
     *
     * @return void
     * @author zhaosong
     */
    public function index()
    {
        if (isPost()) {
            json(Categorylogic::indexlogic());
        }
    }
    
    /**
     * 添加分类。
     *
     * @return void
     * @author zhaosong
     */
    public function add()
    {
        if (isPost()) {
            json(Categorylogic::addlogic(Request()::post()));
        }
    }
    
    /**
     * 获取分类字段列表。
     *
     * @return void
     * @author zhaosong
     */
    public function getFieldList()
    {
        if (isPost()) {
            json(Categorylogic::getFieldListlogic());
        }
    }
    
    /**
     * 获取分类更新信息。
     *
     * @return void
     * @author zhaosong
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Categorylogic::getUpdateInfoslogic(Request()::post('catid')));
        }
    }
    
    /**
     * 更新分类信息。
     *
     * @return void
     * @author zhaosong
     */
    public function update()
    {
        if (isPost()) {
            json(Categorylogic::updatelogic(Request()::post()));
        }
    }
    
    /**
     * 更新分类状态。
     *
     * @return void
     * @author zhaosong
     */
    public function status()
    {
        if (isPost()) {
            json(Categorylogic::statuslogic(Request()::post()));
        }
    }
    
    /**
     * 删除分类。
     *
     * @return void
     * @author zhaosong
     */
    public function delete()
    {
        if (isPost()) {
            json(Categorylogic::deletelogic(Request()::post('catid')));
        }
    }
}