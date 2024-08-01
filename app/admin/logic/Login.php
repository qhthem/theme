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
namespace app\admin\logic;

class Login
{
    /**
     * 用户登录逻辑处理
     *
     * @param array $params 登录表单提交的数据
     * @return array 返回处理结果
     */
    public static function loginlogic(array $params)
    {
        self::Cache_error_verify();
        
        $admin = db('admin')->where(['adminname'=>$params['account']])->find();
        $password = md5s($params['password']);
        
        if(!captcha_check($params['code'])){
            return (['status'=>0,'msg'=>'验证码不正确']);
        }
        
        if (empty($admin)) {
            return (['status'=>0,'msg'=>'用户名不存在']);
        }
        
        if ($admin['password'] !== $password) {
            self::admin_loginlogic($params,0);
            self::Cache_error_logic();
        }
        
        $r = db('admin_role')->where(['roleid'=>$admin['roleid']])->value('disabled');
		if(!empty($r)) {
			return (['status'=>0,'msg'=>'该账户被禁用']);
		}
		
		$Session = 
		[
		    'adminid'=>$admin['adminid'],
		    'adminname'=>$admin['adminname'],
		    'roleid'=>$admin['roleid'],
		    'rolename'=>$admin['rolename']
		];
		
		Session()::set('admininfo',$Session);
		db('admin')->where(['adminid'=>$admin['adminid']])->update(['logintime'=>time(),'loginip'=>getip()]);
		
		self::admin_loginlogic($params,1);
		return (['status'=>200,'msg'=>'登录成功','url'=>'/'.admin_map('admin')]);
    }
    
    /**
     * 记录用户登录逻辑
     *
     * @param array $params 登录表单提交的数据
     * @param int $type 登录类型（0：登录失败，1：登录成功）
     * @return bool 返回记录结果
     */
    public static function admin_loginlogic(array $params,int $type){
        $log['logintime'] = time();
		$log['loginip'] = getip();
		$log['cause'] = $type ? '登录成功':'登录失败';
		$log['adminname'] = $params['account'];
		$log['password'] = empty($type) ? $params['password']:'';
        $log['loginresult'] = $type;
        
        return db('admin_login_log')->insert($log);
    }
    
    /**
     * 用户退出登录
     *
     * @return void
     */
    public static function logoutlogic() {
        Session()::delete('admininfo');
		showmsg("退出成功!", url(''.admin_map('admin').'/login/index'));
	}
	
    /**
     * 缓存错误逻辑处理
     * @return array 返回错误信息数组
     * @author zhaosong
     */
    private static function Cache_error_logic()
    {
        $Cache_name = 'admin_login'.getip();
        // 获取缓存中的错误次数
        $error = Cache()->get($Cache_name);
       
        // 如果没有错误次数记录，则初始化为1
        if (empty($error)) {
            Cache()->set($Cache_name,0,21600);
        }
        
        // 错误次数加1
        $error = Cache()->inc($Cache_name, 1);
        
        // 返回错误信息
        return  json(['status' => 0, 'msg' => "用户名密码错误,错误{$error}次系统已记录"]);
    }
    
    
    /**
     * 缓存错误验证处理
     * @return array 返回错误信息数组
     * @author zhaosong
     */
    private static function Cache_error_verify()
    {
        // 获取缓存中的错误次数
        $error = Cache()->get('admin_login'.getip());
        
        // 如果错误次数为3次，则锁定6小时
        if ($error >= 3) {
            return json(['status' => 0, 'msg' => '密码错误已经超过三次！,请6小时后再尝试']);
        }
    }
    
    
}