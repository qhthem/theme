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

/**
 * 作者: zhaosong
 * 类名: Member
 * 功能: 用户相关操作
 */
class Member
{
    /**
     * 获取用户列表
     *
     * @param array $params 参数
     * @return array
     */
    public static function indexlogic(array $params)
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $where = [];
        $where['username'] = !empty($params['username']) ? ['LIKE', '%'.$params['username'].'%'] : null;
        if (isset($params['status'])) {
            if (!empty($params['status'])) {
                $where['status'] = $params['status'];
            } else {
                if (is_numeric($params['status'])) {
                    $where['status'] = 0;
                } else {
                    $where['status'] = null;
                }
            }
        } else {
            $where['status'] = null;
        }
        
        $time = !empty($params['time']) ? $params['time'] : null;
        if(!empty($params['time'])){
            $where['create_time'] = ['between', [$time[0], $time[1]]];
        }

        $field = 'userid,username,sex,create_time,nickname,email,amount,point,userpic,status';
        
        $res = db('member')->where($where)->field($field)->order('userid DESC')->paginate($page, $limit);

        $res['data'] = $Traverse->factory($res['data'],[],'userid');

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;
    }
    
    /**
     * 添加用户
     *
     * @param array $params 参数
     * @return array
     */
    public static function addlogic(array $params)
    {
        validatePostValues('username,password',$params);
        if (empty($params['password'])) return ['status' => 0, 'msg' => '参数错误,请先输入密码'];
        $params['password'] = md5s($params['password']);
        $params['create_time'] = time();
        $params['groupid'] = 1;
        $params['ip'] = getip();
        $params['userpic'] = !empty($params['userpic']) ?$params['userpic']:'';
        db('member')->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
    }
    
    /**
     * 获取用户信息
     *
     * @param int $userid 用户ID
     * @return array
     */
    public static function getUpdateInfoslogic(int $userid)
    {
        if (!$userid) return ['status' => 0, 'msg' => '参数错误'];
        
        $Field = 'userid,username,nickname,email,amount,point,userpic,status,password';
        $res = db('member')->where(['userid' => $userid])->field($Field)->find();
        $res['point'] = (int) $res['point'];
        $res['amount'] = (int) $res['amount'];
        $res['password'] = '';
        return (['status' => 200, 'data' => $res]);
    }
    
    /**
     * 更新用户信息
     *
     * @param array $params 参数
     * @return array
     */
    public static function updatelogic(array $params)
    {
        if ($params['password']) {
            $params['password'] = md5s($params['password']);
        } else {
            unset($params['password']);
        }
        
        $params['userpic'] = !empty($params['userpic']) ?$params['userpic']:'';

        db('member')->where(['userid' => $params['userid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 删除用户
     *
     * @param string $id 用户ID
     * @return array
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return (['status' => 0, 'msg' => '参数错误']);
        db('member')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }
    
    /**
     * 更新用户状态
     *
     * @param array $params 参数
     * @return array
     */
    public static function statuslogic(array $params)
    {
        if(empty($params['userid'])){
            return (['status'=>00,'msg'=>'参数错误']);
        }
        
        db('member')->where(['userid' => $params['userid']])->update($params);
        return (['status'=>200,'msg'=>'操作成功']);
    }
}