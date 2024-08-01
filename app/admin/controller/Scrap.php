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
use app\admin\logic\Scrap as Scraplogic;

class Scrap extends Base
{
    /**
     * 获取采集内容列表
     *
     * @author zhaosong
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Scraplogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 添加采集内容
     *
     * @author zhaosong
     * @param array $data 采集内容数据
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Scraplogic::addlogic(Request()::post()));
        }
    }

    /**
     * 删除采集内容
     *
     * @author zhaosong
     * @param int $id 采集内容ID
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Scraplogic::deletelogic(Request()::post('id')));
        }
    }

    /**
     * 获取采集内容分类列表
     *
     * @author zhaosong
     * @return void
     */
    public function catid()
    {
        if (isPost()) {
            json(Scraplogic::catidlogic());
        }
    }
}