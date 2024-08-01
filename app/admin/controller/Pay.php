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
use app\admin\logic\Pay as Paylogic;
/**
 * 账单类
 *
 * @author zhaosong
 */
class Pay extends Base
{
    /**
     * 账单方法
     *
     * @param Request $request 请求对象
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Paylogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 删除账单列表
     *
     * @param Request $request 请求对象
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Paylogic::deletelogic(Request()::post('id')));
        }
    }
}