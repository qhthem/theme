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
 * 用户组逻辑处理类
 * @author zhaosong
 */
class Group
{
    /**
     * 获取用户组列表
     *
     * @param array $params 请求参数，包含 limit 和 page
     * @return array 返回状态码和数据
     */
    public static function indexlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 20;
        $page = !empty($params['page']) ? $params['page'] : 1;
        
        $res = db('group')->paginate($page, $limit);

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;
    }
    
    /**
     * 获取用户组字段列表
     *
     * @return array 返回状态码和数据
     */
    public static function getFieldListlogic()
    {
        $group_auth = db('group_auth')->select();
        foreach ($group_auth as $k => $res) {
            $group_auth[$k]['label'] = 
            $group_auth[$k]['authname'].'('.$group_auth[$k]['app'].'/'.$group_auth[$k]['controller'].'/'.$group_auth[$k]['action'].')';
            $group_auth[$k]['value'] = $group_auth[$k]['id'];
            unset($group_auth[$k]['id'], $group_auth[$k]['authname']);
        }
        return ['status' => 200, 'data' => $group_auth];
    }
    
    /**
     * 用户组授权
     *
     * @param array $params 请求参数，包含 authname 等
     * @return array 返回状态码和消息
     */
    public static function authlogic(array $params)
    {
        $requiredFields = [
            'authname' => '权限名称',
            'app' => 'app名称',
            'controller' => 'controller名称',
            'action' => 'action名称'
        ];
        
        foreach ($requiredFields as $field => $fieldName) {
            if (empty($params[$field])) {
                return ['status' => 0, 'msg' => $fieldName . '不能为空'];
            }
        }
        $tablename = db('group_auth');
        $data = $tablename->where(['authname' => $params['authname']])->value('authname');
        if($data){
            return ['status' => 0, 'msg' => '该权限名称已经存在'];
        }
        $tablename->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
    }
    
    /**
     * 添加用户组
     *
     * @param array $params 请求参数，包含 authority 等
     * @return array 返回状态码和消息
     */
    public static function addlogic(array $params)
    {
        validatePostValues('name,experience',$params);
        
        if(!empty($params['authority'])) {
            $params['authority'] = join(',',$params['authority']);
        } else {
            $params['authority'] = '';
        }
        
        db('group')->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
    }
    
    /**
     * 获取用户组更新信息
     *
     * @param int $groupid 用户组 ID
     * @return array 返回状态码和数据
     */
    public static function getUpdateInfoslogic(int $groupid)
    {
        if (!$groupid) return ['status' => 0, 'msg' => '参数错误'];
        
        $res = db('group')->where(['groupid' => $groupid])->find();
        $res['experience'] = (int) $res['experience'];
        $res['authority'] = array_map(function ($value) {
            return (int) $value;
        }, explode(',', $res['authority']));
        return (['status' => 200, 'data' => $res]);
    }
    
    /**
     * 更新用户组
     *
     * @param array $params 请求参数，包含 groupid 等
     * @return array 返回状态码和消息
     */
    public static function updatelogic(array $params)
    {
        if(!empty($params['authority'])) {
            $params['authority'] = join(',',$params['authority']);
        } else {
            $params['authority'] = '';
        }
        
        db('group')->where(['groupid' => $params['groupid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 删除用户组
     *
     * @param string $groupid 用户组 ID，多个用户组 ID 以逗号分隔
     * @return array 返回状态码和消息
     */
    public static function deletelogic(string $groupid)
    {
        if (empty($groupid)) return (['status' => 0, 'msg' => '参数错误']);
        db('group')->destroy(explode(',',$groupid));
        return (['status' => 200, 'msg' => '操作成功']);
    }
    
    /**
     * 删除逻辑组权限节点
     * @param string $id 以逗号分隔的权限节点ID字符串
     * @return array 返回操作状态和消息
     * @author zhaosong
     */
    public static function dellogic(string $id)
    {
        // 检查参数是否为空
        if (empty($id)) {
            // 参数错误返回状态码0和错误消息
            return (['status' => 0, 'msg' => '参数错误']);
        }
        // 使用explode函数将逗号分隔的字符串转换为数组，并删除对应的权限节点
        db('group_auth')->destroy(explode(',',$id));
        // 操作成功返回状态码200和成功消息
        return (['status' => 200, 'msg' => '操作成功']);
    }
}