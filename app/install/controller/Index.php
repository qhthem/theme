<?php
// +----------------------------------------------------------------------
// | 2023-12-18 [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Powered By Astro-程序安装入口控制器文件
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace app\install\controller;
use app\install\logic\Index as Indexlogic;

class Index
{
    /**
     * 首页展示
     *
     * @author zhaosong
     * @return void
     */
    public function index(): void
    {
        if (Request()::isGet()) {
            Indexlogic::indexlogic();
        }
    }

    /**
     * API接口
     *
     * @author zhaosong
     * @return void
     */
    public function api(): void
    {
        if (Request()::isPost()) {
            json(Indexlogic::apilogic());
        }
    }

    /**
     * 创建数据
     *
     * @author zhaosong
     * @param array $data 创建数据所需的参数
     * @return void
     */
    public function createdata(): void
    {
        if (Request()::isPost()) {
            json(Indexlogic::createdata(Request()::post()));
        }
    }

    /**
     * 获取进度信息
     *
     * @author zhaosong
     * @param array $data 获取进度信息所需的参数
     * @return void
     */
    public function progress(): void
    {
        if (Request()::isPost()) {
            json(Indexlogic::progress(Request()::post()));
        }
    }
}