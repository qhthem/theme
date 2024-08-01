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
use app\admin\model\Handle;

class Admin
{
    /**
     * 获取管理员登录日志列表
     *
     * @param array $params 请求参数，包括分页、排序等信息
     * @return array 返回包含管理员登录日志列表的数组
     */
    public static function login_loglogic($params = [])
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $field = 'id,adminname,logintime,loginip,cause,password,loginresult';
        $res = db('admin_login_log')->field($field)->order('id DESC')->paginate($page, $limit);
        
        $res['data'] = $Traverse->factory($res['data'],['field'=>'password','value'=>'******']);
        
        $data['status'] = 200;
        $data['data'] = $res;
        return ($data);
    }
    
    /**
     * 删除管理员登录日志
     *
     * @param int $id 要删除的日志的 ID
     * @return array 返回包含操作结果的数组
     */
    public static function deletelogic($id)
    {
        if (!$id) return (['status' => 0, 'msg' => '参数错误']);
        db('admin_login_log')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }
    
    /**
     * 清除缓存和错误日志
     *
     * @return array 返回包含操作结果的数组
     */
    public static function clearlogic()
    {
        cache_type()->clearCache();
        Handle::del_target_dir(ROOT_PATH.'app/common/log/',false);
        Handle::del_target_dir(app()::getRuntimePath(),false);
        return (['status'=>200,'msg'=>'缓存，错误日志，清除成功']);
    }
}