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
use app\admin\logic\Admin as Adminlogic;

class Admin extends Base
{
    /**
     * 获取管理员登录日志列表
     *
     * @return void
     */
    public function login_log()
    {
        if (isPost()) {
            json(Adminlogic::login_loglogic(Request()::post()));
        }
    }
    
    /**
     * 删除管理员登录日志
     *
     * @return void
     */
    public function delete()
    {
        if (isPost()) {
            json(Adminlogic::deletelogic(Request()::post('id')));
        }
    }
    
    /**
     * 清除缓存和错误日志
     *
     * @return void
     */
    public function clear()
    {
        if (isPost()) {
            json(Adminlogic::clearlogic());
        }
    }
}