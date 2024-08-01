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
class Role
{
    /**
     * 获取角色列表
     *
     * @param array $params
     * @return array
     */
    public static function indexlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 20;
        $page = !empty($params['page']) ? $params['page'] : 1;
        $field = 'roleid,rolename,description,system,disabled';
        $res = db('admin_role')->field($field)->order('roleid DESC')->paginate($page, $limit);

        $data['status'] = 200;
        $data['data'] = $res;
        return ($data);
    }

    /**
     * 添加角色
     *
     * @param array $params
     * @return array
     */
    public static function addlogic(array $params)
    {
        if (Session()::get('admininfo')['roleid'] !== 1) {
            return (['status' => 0, 'msg' => '您无权添加该角色，请更换角色']);
        }
        $params['system'] = 1;
        db('admin_role')->insert($params);
        return (['status' => 200, 'msg' => '添加成功']);
    }

    /**
     * 获取更新信息
     *
     * @param int $roleid
     * @return array
     */
    public static function getUpdateInfoslogic($roleid)
    {
        if (!$roleid) return (['status' => 0, 'msg' => '参数错误']);
        $Field = 'roleid,rolename,description,system,disabled';
        $res = db('admin_role')->where(['roleid' => $roleid])->field($Field)->find();
        $res['disabled'] = strval($res['disabled']);
        return (['status' => 200, 'data' => $res]);
    }

    /**
     * 更新角色
     *
     * @param array $params
     * @return array
     */
    public static function updatelogic(array $params)
    {
        if (Session()::get('admininfo')['roleid'] !== 1) {
            return (['status' => 0, 'msg' => '您无权添加该角色，请更换角色']);
        }
        if ($params['roleid'] == 1) {
            return (['status' => 0, 'msg' => '超级管理员不能修改']);
        }

        db('admin_role')->where(['roleid' => $params['roleid']])->update($params);
        return (['status' => 200, 'msg' => '更新成功']);
    }

    /**
     * 获取角色权限
     *
     * @param int $roleid
     * @return array
     */
    public static function privlogic($roleid)
    {
        $idx = $roleid;
        $role_priv = db('admin_role_priv')
        ->field('typeid')
        ->alias('a')
        ->join(C('db_prefix').'menu w', 'a.typeid = w.id')
        ->where(['roleid' => $idx])
        ->select();
        
        $id = [];
        foreach ($role_priv as $val) {
            $id[] = (int)$val['typeid'];
        }
        
        return (['status' => 200, 'roleid' => $idx, 'data' => $id]);
    }

    /**
     * 获取字段列表
     *
     * @return array
     */
    public static function getFieldListlogic()
    {
        $list = db('menu')->field('id,name,parentid')->select();
        $lists = Handle::_generateListTree($list, 0, ['id', 'parentid']);
        return (['status' => 200, 'data' => $lists]);
    }

    /**
     * 角色权限
     *
     * @param array $params
     * @return array
     */
    public static function role_privlogic(array $params)
    {
        if (count($params['fx']) > 0) {
            db('admin_role_priv')->where(['roleid' => $params['roleid']])->delete();
            foreach ($params['fx'] as $val) {
                // 查询菜单ID 并找到相关的值
                $data = db('menu')->where(['id' => $val])->field('m,c,a,data')->find();
                $r = db('admin_role_priv')->insert(['roleid' => $params['roleid'], 'typeid' => $val, 'm' => $data['m'], 'c' => $data['c'], 'a' => $data['a'], 'data' => $data['data']]);
            }
        } else {
            $r = db('admin_role_priv')->where(['roleid' => $params['roleid']])->delete();
        }
        return (['status' => 200, 'msg' => '操作成功']);
    }

    /**
     * 删除角色
     *
     * @param int $roleid
     * @return array
     */
    public static function deletelogic($roleid)
    {
        if (!$roleid) return (['status' => 0, 'msg' => '参数错误']);
        if ($roleid == 1) return (['status' => 0, 'msg' => '超级管理员不能删除']);
        db('admin_role')->where(['roleid' => $roleid])->delete();
        return (['status' => 200, 'msg' => '操作成功']);
    }
}