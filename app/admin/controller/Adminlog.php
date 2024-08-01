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
use app\admin\logic\Adminlog as Adminloglogic;

class Adminlog extends Base
{
    /**
     * 获取管理员日志列表
     *
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Adminloglogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 删除管理员日志
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Adminloglogic::deletelogic(Request()::post('id')));
        }
    }
}