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
use app\index\logic\Search as Searchlogic;

class Search
{
    /**
     * 搜索首页
     *
     * @author zhaosong
     */
    public function index(): void
    {
        if (Request()::isGet()) {
            Searchlogic::indexlogic();
        }
    }
}