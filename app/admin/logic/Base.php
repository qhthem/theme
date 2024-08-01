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

class Base
{
    /**
     * 检查用户登录状态
     *
     * @return void
     */
    public static function checkLoginlogic()
    {
        $admininfo = Session()::get('admininfo');
        if(empty($admininfo)){
            return true;
        }
    }

    /**
     * 记录操作日志
     *
     * @return void
     */
    public static function logOperationlogic()
    {
       $app = Router()->formatAppnName();
       $Action = Router()->formatActionName();
       if(in_array($Action,['add','update','delete'])) {
			$content = request()::except(['s', '_pjax']);
			if ($content) {
				foreach ($content as $k => $v) {
					if (is_string($v) && strlen($v) > 200 || stripos($k, 'password') !== false) {
						unset($content[$k]);
					}
				}
			}
			$data['app'] = $app;
			$data['controller'] = Router()->formatClassName();
			$data['adminname'] = Session()::get('admininfo')['adminname'];
			$data['querystring'] = request()::getCurrentUrl();
			$data['ip'] = getip();
			$data['content'] = json_encode($content,JSON_UNESCAPED_UNICODE);
			$data['create_times'] = time();
			db('admin_log')->insert($data);
		}
    }

    /**
     * 检查用户权限
     *
     * @return void
     */
    public static function checkPermissionlogic()
    {
        $roleId = Session()::get('admininfo')['roleid'];
        $role_m = GetRoute('app');
        $role_c = strtolower(GetRoute('controller'));
        $role_a = GetRoute('action');
        if($roleId == 1) return false;
        $where['m'] = $role_m;
        $where['c'] = $role_c;
        $where['a'] = $role_a;
        $where['roleid'] = $roleId;
        $Route_role = db('admin_role_priv')->where($where)->find();
        if(empty($Route_role)){
            return json(['status'=>0,'msg'=>'该角色没有执行权限！']);
        }
        if($Route_role['m'] == $role_m && strtolower($Route_role['c']) == $role_c && $Route_role['a'] == $role_a){
            return false;
        }
        else{
            return json(['status'=>0,'msg'=>'该角色没有执行权限！']);
        }
    }

    /**
     * 验证请求token
     *
     * @return void
     */
    public static function verifyRequestTokenlogic()
    {
       $payload = self::RequestTokenlogic();
       $token = Request()::header('Qh-token');
       if(empty($token)){
           return ['status'=>0,'msg'=>'验证请求token失败！'];
       }
       else {
          if($payload == $token){
              return ['status'=>1,'msg'=>'验证请求token成功！'];
          }
          else{
              return ['status'=>0,'msg'=>'验证请求token失败！'];
          }
       }
       
    }
    
    /**
     * 获取请求令牌的逻辑
     *
     * @return string 生成的令牌
     */
    public static function RequestTokenlogic()
    {
        $admininfo = Session()::get('admininfo');
        $token = jwt()->encode(array_merge(['today'=>date('Ymd'),'c'=>$_SERVER['HTTP_HOST']],$admininfo));
        return $token;
    }
    
}