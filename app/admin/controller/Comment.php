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
use app\admin\logic\Comment as Commentlogic;

class Comment extends Base
{
    /**
     * 评论列表
     *
     * @author zhaosong
     */
    public function index()
    {
        if (isPost()) {
            json(Commentlogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 更新评论状态
     *
     * @author zhaosong
     */
    public function status()
    {
        if (isPost()) {
            json(Commentlogic::statuslogic(Request()::post()));
        }
    }

    /**
     * 删除评论
     *
     * @author zhaosong
     */
    public function delete()
    {
        if (isPost()) {
            json(Commentlogic::deletelogic(Request()::post()));
        }
    }
}