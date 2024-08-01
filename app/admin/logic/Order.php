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

class Order
{
    /**
     * 获取订单列表
     *
     * @param array $params 参数数组，包含以下可选参数：
     *   - limit: 每页显示的订单数量，默认为20
     *   - page: 当前页码，默认为1
     *   - trade_sn: 订单号，用于模糊搜索
     *   - status: 订单状态，默认为0
     *   - time: 订单创建时间范围，格式为 ['开始时间', '结束时间']
     * @return array 返回订单列表数据和状态信息
     * @author zhaosong
     */
    public static function indexlogic(array $params = [])
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $where = [];
        $where['order_sn'] = !empty($params['trade_sn']) ? ['LIKE', '%'.$params['trade_sn'].'%'] : null;
        $where['status'] = isset($params['status']) ? $params['status'] :null;
        $time = !empty($params['time']) ? $params['time'] : null;
        if(!empty($params['time'])){
            $where['addtime'] = ['between', [$time[0], $time[1]]];
        }
        
        $res = db('order')->where($where)
        ->order('id DESC')->paginate($page, $limit);
        $res['data'] = array_map(function($item) {
            $item['addtime'] = date("Y-m-d H:i:s", $item['addtime']);
            $item['paytime'] = !empty($params['paytime']) ?date("Y-m-d H:i:s", $item['paytime']):'未支付';
            $item['userid'] = get_userinfo($item['userid'],'nickname');
            return $item;
        }, $res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;
    }
    
    /**
     * 删除订单
     *
     * @param string $id 订单ID，多个ID用逗号分隔
     * @return array 返回操作状态和信息
     * @author zhaosong
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return (['status' => 0, 'msg' => '参数错误']);
        db('order')->destroy(explode(',',$id));
        return (['status' => 200, 'msg' => '操作成功']);
    }
}