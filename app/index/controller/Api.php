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
namespace app\index\controller;
use app\index\logic\Api as Apilogic;
use app\index\logic\Comment;

class Api
{
    /**
     * 获取内容
     */
    public function content(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::contentlogic(Request()::Post()));
        }
    }

    /**
     * 点赞或收藏
     */
    public function like_favorite(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::like_favoritelogic(Request()::Post()));
        }
    }

    /**
     * 发表评论
     */
    public function comment(): void
    {
        if (Request()::isPost()) {
            json(Comment::commentlogic(Request()::Post()));
        }
    }

    /**
     * 获取评论列表
     */
    public function comment_list(): void
    {
        if (Request()::isPost()) {
            json(Comment::comment_list(Request()::Post()));
        }
    }
}