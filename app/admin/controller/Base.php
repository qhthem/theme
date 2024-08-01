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
use app\admin\logic\Base as B;

/**
 * 基本控制器类
 */
class Base
{
    
    /**
     * 构造函数，检查用户登录状态、记录操作日志、检查用户权限和验证请求token
     */
    public function __construct()
    {
        $this->checkLogin();
        $this->logOperation();
        $this->checkPermission();
        if(isPost()){
            $this->verifyRequestToken();
        }
    }

    /**
     * 检查用户登录状态
     *
     * @return void
     */
    protected function checkLogin()
    {
        $checkLogin = B::checkLoginlogic();
        
        if(!empty($checkLogin)){
            showmsg('登录过期', url(''.admin_map('admin').'/login/index'),false);
        }
    }

    /**
     * 记录操作日志
     *
     * @return void
     */
    protected function logOperation()
    {
        $roleId = Session()::get('admininfo')['roleid'];
        if (C('isoperation_Log') && $roleId !== 1)
        {
            B::logOperationlogic();
        }
    }

    /**
     * 检查用户权限
     *
     * @return void
     */
    protected function checkPermission()
    {
        if (isPost()) {
            B::checkPermissionlogic();
        }
    }

    /**
     * 创建请求令牌
     *
     * @return void
     */
    protected static function RequestToken()
    {
        $token = B::RequestTokenlogic();
        if (empty($token)){
            json(['status'=>0,'msg'=>'token创建失败']);
        }
    }

    /**
     * 验证请求token
     *
     * @return void
     */
    protected function verifyRequestToken()
    {
        $token = B::verifyRequestTokenlogic();
        if(!empty($token)){
            if($token['status'] == 1){
                return false;
            }
            else {
                json($token);
            }
        }
        else{
            json(['status'=>0,'msg'=>'验证请求token失败！']);
        }
    }
}