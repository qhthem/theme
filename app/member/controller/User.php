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
namespace app\member\controller;
use app\member\logic\User as Userlogic;

/**
 * User 类
 *
 * @author zhaosong
 */
class User
{
    /**
     * 首页逻辑
     */
    public function index(): void
    {
        if (Request()::isGet()) {
            (new Userlogic())->indexlogic(Request()::get('userid'));
        }
    }
}