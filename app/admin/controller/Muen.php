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
use app\admin\logic\Muen as Muenlogic;

class Muen extends Base
{
    /**
     * 获取菜单列表。
     *
     * @return void
     */
    public function index()
    {
        if (isPost()){
            json(Muenlogic::indexlogic());
        }
    }
    
    /**
     * 添加菜单。
     *
     * @return void
     */
    public function add()
    {
        if (isPost()){
            json(Muenlogic::addlogic(Request()::post()));
        }
    }
    
    /**
     * 更新菜单状态。
     *
     * @return void
     */
    public function status()
    {
        if (isPost()){
            json(Muenlogic::statuslogic(Request()::post()));
        }
    }
    
    /**
     * 获取菜单更新信息。
     *
     * @return void
     */
    public function getUpdateInfos()
    {
        if (isPost()){
            json(Muenlogic::getUpdateInfoslogic(Request()::post('id')));
        }
    }
    
    /**
     * 获取菜单更新详细信息。
     *
     * @return void
     */
    public function getUpdateInfo()
    {
        if (isPost()){
            json(Muenlogic::getUpdateInfologic(Request()::post()));
        }
    }
    
    /**
     * 获取菜单字段列表。
     *
     * @return void
     */
    public function getFieldList()
    {
        if (isPost()){
            json(Muenlogic::getFieldListlogic(Request()::post('id')));
        }
    }
    
    /**
     * 删除菜单。
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()){
            json(Muenlogic::deletelogic(Request()::post('id')));
        }
    }
}