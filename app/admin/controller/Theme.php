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
use app\admin\logic\Theme as Themelogic;

/**
 * Theme 类，继承自 Base 类
 * @author zhaosong
 */
class Theme extends Base
{
    /**
     * 索引方法
     */
    public function index()
    {
        if (isPost()) {
            // 以 JSON 格式返回 Themelogic 的 indexlogic 处理结果，参数为请求的 POST 数据
            json(Themelogic::indexlogic(Request()::post()));
        }
    }

    /**
     * 更新方法
     */
    public function update()
    {
        if (isPost()) {
            // 以 JSON 格式返回 Themelogic 的 updatelogic 处理结果，参数为请求的 POST 数据
            json(Themelogic::updatelogic(Request()::post()));
        }
    }

    /**
     * 主题相关方法
     */
    public function theme()
    {
        if (isPost()) {
            // 以 JSON 格式返回 Themelogic 的 _app_theme 处理结果，参数为请求 POST 中的 'app' 数据
            json(Themelogic::_app_theme(Request()::post('app')));
        }
    }

    /**
     * 获取更新信息方法
     */
    public function getUpdateInfos()
    {
        if (isPost()) {
            // 以 JSON 格式返回 Themelogic 的 getUpdateInfos 处理结果，参数为请求 POST 中的 'app' 数据
            json(Themelogic::getUpdateInfos(Request()::post('app')));
        }
    }
}
