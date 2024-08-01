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

class Link
{
    /**
     * 获取链接列表
     *
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function indexlogic($params = [])
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $res = db('link')->order('id DESC')->paginate($page, $limit);
        $res['data'] = $Traverse->factory($res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return ($data);
    }

    /**
     * 添加链接
     *
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function addlogic(array $params)
    {
        if(empty($params['name'])){
            return (['status'=>00,'msg'=>'网站名称不能为空']);
        }
        
        if(empty($params['url'])){
            return (['status'=>00,'msg'=>'链接不能为空']);
        }
        
        $params['inputtime'] = time();
        
        db('link')->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
    }

    /**
     * 更新链接状态
     *
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function statuslogic(array $params)
    {
        if (empty($params['id'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
    
        db('link')->where(['id' => $params['id']])->update($params);
        return ['status' => 200, 'msg' => '操作成功'];
    }

    /**
     * 获取链接更新信息
     *
     * @param int $id 链接ID
     * @return array 返回结果
     */
    public static function getUpdateInfoslogic($id)
    {
        if (!$id) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('link')->where(['id' => $id])->find();
        
        return ['status' => 200, 'data' => $res];
    }

    /**
     * 删除链接
     *
     * @param string $id 链接ID，多个ID用逗号分隔
     * @return array 返回结果
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return (['status' => 0, 'msg' => '参数错误']);
        db('link')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }

    /**
     * 更新链接
     *
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function updatelogic(array $params)
    {
        db('link')->where(['id' => $params['id']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
}