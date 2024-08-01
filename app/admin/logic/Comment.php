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

class Comment
{
    /**
     * 获取评论列表
     *
     * @param array $params 参数数组，包含以下可选参数：
     *   - limit: 每页显示的评论数量，默认为20
     *   - page: 当前页码，默认为1
     *   - content: 评论内容关键词，用于模糊搜索
     *   - time: 评论创建时间范围，格式为 ['开始时间', '结束时间']
     * @return array 返回评论列表数据和状态信息
     * @author zhaosong
     */
    public static function indexlogic(array $params = [])
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $where = [];
        $where['content'] = !empty($params['content']) ? ['LIKE', '%'.$params['content'].'%'] : null;
        $where['status'] = isset($params['status']) ? $params['status'] :null;
        $time = !empty($params['time']) ? $params['time'] : null;
        if(!empty($params['time'])){
            $where['creat_time'] = ['between', [$time[0], $time[1]]];
        }
        
        $res = db('comments')->where($where)->field('id,modelid,userid,content,creat_time,ip,status')
        ->order('id DESC')->paginate($page, $limit);
        $res['data'] = $Traverse->factory($res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;
    }
    
    /**
     * 更新评论状态
     *
     * @param array $params 参数数组，包含以下参数：
     *   - id: 评论ID
     *   - status: 评论状态，1为启用，0为禁用
     * @return array 返回操作状态和信息
     * @author zhaosong
     */
    public static function statuslogic(array $params)
    {
        if (empty($params['id'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
    
        db('comments')->where(['id' => $params['id']])->update($params);
        return ['status' => 200, 'msg' => '操作成功'];
    }
    
    /**
     * 删除评论
     *
     * @param string $id 评论ID，多个ID用逗号分隔
     * @return array 返回操作状态和信息
     * @author zhaosong
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return (['status' => 0, 'msg' => '参数错误']);
        db('comments')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }
    
}