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
use app\admin\logic\Order as Orderlogic;

class Order extends Base
{
    /**
     * 获取订单列表
     *
     * @return void 输出 JSON 格式的订单列表数据
     * @author zhaosong
     */
    public function index()
    {
        if (isPost()) {
            json(Orderlogic::indexlogic(Request()::post()));
        }
    }
    
    /**
     * 删除订单
     *
     * @return void 输出 JSON 格式的操作状态和信息
     * @author zhaosong
     */
    public function delete()
    {
        if (isPost()) {
            json(Orderlogic::deletelogic(Request()::post()));
        }
    }
    
}