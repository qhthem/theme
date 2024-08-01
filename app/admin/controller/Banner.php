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
use app\admin\logic\Banner as Bannerlogic;

class Banner extends Base
{
    /**
     * Author: zhaosong
     * Function: 获取Banner列表
     * param: 无
     * return void
     */
    public function index()
    {
        if (isPost()) {
            json(Bannerlogic::indexlogic(Request()::post()));
        }
    }
    
    /**
     * Author: zhaosong
     * Function: 添加Banner
     * param: 无
     * return void
     */
    public function add()
    {
        if (isPost()) {
            json(Bannerlogic::addlogic(Request()::post()));
        }
    }
    
    /**
     * Author: zhaosong
     * Function: 更新Banner状态
     * param: 无
     * return void
     */
    public function status()
    {
        if (isPost()) {
            json(Bannerlogic::statuslogic(Request()::post()));
        }
    }
    
    /**
     * Author: zhaosong
     * Function: 获取Banner更新信息
     * param: 无
     * return void
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Bannerlogic::getUpdateInfoslogic(Request()::post('id')));
        }
    }
    
    /**
     * Author: zhaosong
     * Function: 删除Banner
     * param: 无
     * return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Bannerlogic::deletelogic(Request()::post('id')));
        }
    }
    
    /**
     * Author: zhaosong
     * Function: 更新Banner信息
     * param: 无
     * return void
     */
    public function update()
    {
        if (isPost()) {
            json(Bannerlogic::updatelogic(Request()::post()));
        }
    }
    
}