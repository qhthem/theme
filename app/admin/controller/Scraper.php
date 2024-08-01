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
use app\admin\logic\Scraper as Scraperlogic;

class Scraper extends Base
{
    /**
     * 获取爬虫列表
     *
     * @author zhaosong
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Scraperlogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 添加爬虫
     *
     * @author zhaosong
     * @param array $data 爬虫数据
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Scraperlogic::addlogic(Request()::post()));
        }
    }

    /**
     * 更新爬虫
     *
     * @author zhaosong
     * @param array $data 爬虫数据
     * @return void
     */
    public function update()
    {
        if (isPost()) {
            json(Scraperlogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 获取爬虫更新信息
     *
     * @author zhaosong
     * @param int $nodeid 爬虫ID
     * @return void
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            json(Scraperlogic::getUpdateInfoslogic(Request()::post('nodeid')));
        }
    }

    /**
     * 删除爬虫
     *
     * @author zhaosong
     * @param int $nodeid 爬虫ID
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Scraperlogic::deletelogic(Request()::post('nodeid')));
        }
    }

    /**
     * 测试爬虫
     *
     * @author zhaosong
     * @param int $nodeid 爬虫ID
     * @return void
     */
    public function scraper_test()
    {
        if (isPost()) {
            json(Scraperlogic::scraper_test(Request()::post('nodeid')));
        }
    }

    /**
     * 获取爬虫URL
     *
     * @author zhaosong
     * @param int $nodeid 爬虫ID
     * @return void
     */
    public function scraper_url()
    {
        if (isPost()) {
            json(Scraperlogic::scraper_url(Request()::post('nodeid')));
        }
    }

    /**
     * 获取爬虫进度
     *
     * @author zhaosong
     * @return void
     */
    public function scraper_progress()
    {
        if (isPost()) {
            json(Scraperlogic::scraper_progress());
        }
    }

    /**
     * 获取爬虫内容
     *
     * @author zhaosong
     * @param int $nodeid 爬虫ID
     * @return void
     */
    public function scraper_content()
    {
        if (isPost()) {
            json(Scraperlogic::scraper_content(Request()::post('nodeid')));
        }
    }
}