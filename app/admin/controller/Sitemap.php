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
use app\admin\logic\Sitemap as Sitemaplogic;

class Sitemap extends Base
{
    /**
     * 获取地图索引信息
     *
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Sitemaplogic::indexlogic());
        }
    }

    /**
     * 生成地图
     *
     * @return void
     */
    public function mian()
    {
        if (isPost()) {
            json(Sitemaplogic::mianlogic(Request()::post()));
        }
    }

    /**
     * 删除地图
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Sitemaplogic::deletelogic());
        }
    }
}