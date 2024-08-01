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
 * 支付账单类
 *
 * @author zhaosong
 */
class Pay 
{
    /**
     * 支付账单列表
     *
     * @param array $params 参数数组
     * @param int $params['limit'] 每页显示数量，默认为 20
     * @param int $params['page'] 当前页数，默认为 1
     * @param string $params['trade_sn'] 订单号
     * @param string $params['username'] 用户名
     * @param array $params['time'] 时间范围，格式为 [开始时间, 结束时间]
     * @return array 分页数据和状态码
     */
    public static function indexlogic($params = [])
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $where = [];
        $where['trade_sn'] = !empty($params['trade_sn']) ? $params['trade_sn'] : null;
        $where['username'] = !empty($params['username']) ? ['LIKE', '%'.$params['username'].'%'] : null;
        $time = !empty($params['time']) ? $params['time'] : null;
        if(!empty($params['time'])){
            $where['creat_time'] = ['between', [$time[0], $time[1]]];
        }
        
        $res = db('pay')->where($where)->order('id DESC')->paginate($page, $limit);
        $res['data'] = $Traverse->factory($res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;
    }
    
    /**
     * 删除支付账单列表记录
     *
     * @param string $id 要删除的记录的 ID
     * @return array 删除结果和状态码
     */    
    public static function deletelogic(string $id)
    {
        if (empty($id)) return (['status' => 0, 'msg' => '参数错误']);
        db('pay')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }
}