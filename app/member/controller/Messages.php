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
use app\member\logic\Messages as Messageslogic;


/**
 * Messages 类
 *
 * @author zhaosong
 */
class Messages
{
    /**
     * 首页逻辑
     */
    public function index(): void
    {
        if (Request()::isGet()) {
            (new Messageslogic())->indexlogic();
        }
    }

    /**
     * 消息列表逻辑
     */
    public function message_list(): void
    {
        if (Request()::isPost()) {
            json((new Messageslogic())->listlogic(Request()::post()));
        }
    }

    /**
     * 消息分页逻辑
     */
    public function message_page(): void
    {
        if (Request()::isPost()) {
            json((new Messageslogic())->polymerize(Request()::post()));
        }
    }

    /**
     * 添加消息逻辑
     */
    public function message_add(): void
    {
        if (Request()::isPost()) {
            json((new Messageslogic())->addlogic(Request()::post()));
        }
    }
}