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
use app\admin\logic\Field as Fieldlogic;

class Field extends Base
{
    /**
     * 获取字段列表
     *
     * @author zhaosong
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Fieldlogic::indexlogic(Request()::post()));
        }
    }
    
    /**
     * 添加字段
     *
     * @author zhaosong
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Fieldlogic::addlogic(Request()::post()));
        }
    }
    
    /**
     * 获取字段列表
     *
     * @author zhaosong
     * @return void
     */
    public function getFieldList()
    {
        if (isPost()) {
            json(Fieldlogic::getFieldListlogic());
        }
    }
    
    /**
     * 更新字段状态
     *
     * @author zhaosong
     * @return void
     */
    public function status()
    {
        if (isPost()) {
            json(Fieldlogic::statuslogic(Request()::post()));
        }
    }
    
    /**
     * 获取更新字段信息
     *
     * @author zhaosong
     * @return void
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Fieldlogic::getUpdateInfoslogic(Request()::post('fieldid')));
        }
    }
    
    /**
     * 更新字段
     *
     * @author zhaosong
     * @return void
     */
    public function update()
    {
        if (isPost()) {
            json(Fieldlogic::updatelogic(Request()::post()));
        }
    }
    
    /**
     * 删除字段
     *
     * @author zhaosong
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Fieldlogic::deletelogic(Request()::post('fieldid')));
        }
    }   
    
}