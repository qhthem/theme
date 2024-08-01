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

class Adminlog
{
    /**
     * 获取管理员日志列表
     *
     * @param array $params 参数数组
     * @return array 返回格式化后的数据
     */
    public static function indexlogic($params = [])
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $field = 'id,adminname,create_times,ip,app,querystring,content,controller';
        $res = db('admin_log')->field($field)->order('id DESC')->paginate($page, $limit);
        $res['data'] = $Traverse->factory($res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return ($data);
    }

    /**
     * 删除管理员日志
     *
     * @param string $id 日志ID
     * @return array 返回操作结果
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return (['status' => 0, 'msg' => '参数错误']);
        db('admin_log')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }
}