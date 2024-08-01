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
use app\admin\logic\System as Systemlogic;

/**
 * 系统相关的控制器
 *
 * @author zhaosong
 */
class System extends Base
{
    /**
     * 获取系统配置的逻辑处理
     *
     * @author zhaosong
     * @return void
     */
    public function index()
    {
        if (isPost()) {
            json(Systemlogic::indexlogic());
        }
    }

    /**
     * 添加系统配置的逻辑处理
     *
     * @author zhaosong
     * @return void
     */
    public function add()
    {
        if (isPost()) {
            json(Systemlogic::addlogic(Request()::post()));
        }
    }

    /**
     * 获取工作台信息的逻辑处理
     *
     * @author zhaosong
     * @return void
     */
    public function workbench()
    {
        if (isPost()) {
            json(Systemlogic::workbenchlogic());
        }
    }

    /**
     * 获取我的图表信息的逻辑处理
     *
     * @author zhaosong
     * @return void
     */
    public function myChart()
    {
        if (isPost()) {
            json(Systemlogic::myChartlogic());
        }
    }
    
    /**
     * 清除缓存
     *
     * @author zhaosong
     * @return void
     */
    public function clear_cache()
    {
        if (isPost()) {
            json(Systemlogic::clear_cache(Request()::post()));
        }
    }
    
    
    /**
     * 测试发送邮箱
     *
     * @author zhaosong
     * @return void
     */
    public function send_email()
    {
        if (isPost()) {
            json(Systemlogic::send_email(Request()::post('email')));
        }
    } 
    
}