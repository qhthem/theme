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

class Manage
{
    /**
     * 获取管理员列表
     *
     * @param array $params 参数数组
     * @return array 返回结果数组
     */
    public static function indexlogic(array $params)
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $where = [];
        $where['adminname'] = !empty($params['name']) ? $params['name'] : null;
        $where['nickname'] = !empty($params['nickname']) ? $params['nickname'] : null;
        $where['roleid'] = !empty($params['roleid']) ? $params['roleid'] : null;

        $field = 'adminid,roleid,rolename,adminname,nickname,logintime,loginip,email';
        
        $res = db('admin')->where($where)->field($field)->order('adminid DESC')->paginate($page, $limit);

        $res['data'] = $Traverse->factory($res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        $data['role'] = array_merge(db('admin_role')->field('roleid,rolename')->select());
        return $data;
    }

    /**
     * 添加管理员
     *
     * @param array $params 参数数组
     * @return array 返回结果数组
     */
    public static function addlogic(array $params)
    {
        validatePostValues('adminname,password',$params);
        
        if (Session()::get('admininfo')['roleid'] !== 1) {
            return ['status' => 0, 'msg' => '您无权添加该角色管理员，请更换角色'];
        }
        $params['password'] = md5s($params['password']);
        $params['addtime'] = time();
        $params['rolename'] = db('admin_role')->where(['roleid' => $params['roleid']])->value('rolename');
        db('admin')->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
    }

    /**
     * 获取角色列表
     *
     * @return array 返回结果数组
     */
    public static function getFieldListlogic()
    {
        $list = db('admin_role')->field('roleid,rolename')->select();
        return ['status' => 200, 'data' => $list];
    }

    /**
     * 更新管理员信息
     *
     * @param array $params 参数数组
     * @return array 返回结果数组
     */
    public static function updatelogic(array $params)
    {
        if (Session()::get('admininfo')['roleid'] !== 1) {
            return ['status' => 0, 'msg' => '您无权添加该角色管理员，请更换角色'];
        }
        if ($params['password']) {
            $params['password'] = md5s($params['password']);
        } else {
            unset($params['password']);
        }

        db('admin')->where(['adminid' => $params['adminid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }

    /**
     * 获取管理员更新信息
     *
     * @param int $adminid 管理员ID
     * @return array 返回结果数组
     */
    public static function getUpdateInfoslogic($adminid)
    {
        if (!$adminid){
            $adminid = Session()::get('admininfo')['roleid'];
        }
        $Field = 'adminid,roleid,rolename,adminname,nickname,email,password';
        $res = db('admin')->where(['adminid' => $adminid])->field($Field)->find();
        $res['password'] = '';
        $res['roleid'] = ($res['roleid']);
        return (['status' => 200, 'data' => $res]);
    }

    /**
     * 删除管理员
     *
     * @param int $adminid 管理员ID
     * @return array 返回结果数组
     */
    public static function deletelogic($adminid)
    {
        if (!$adminid) return ['status' => 0, 'msg' => '参数错误'];
        if (Session()::get('admininfo')['roleid'] !== 1) {
            return ['status' => 0, 'msg' => '您无权删除,请更换角色'];
        }
        db('admin')->where(['adminid' => $adminid])->delete();
        return ['status' => 200, 'msg' => '操作成功'];
    }
}