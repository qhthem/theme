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
use app\admin\logic\Content as C;

class Scrap {
    
    /**
     * 获取爬虫内容列表
     * @author zhaosong
     * @param array $params 请求参数，包括分页和排序信息
     * @return array 返回爬虫内容列表和状态信息
     */
    public static function indexlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 8;
        $page = !empty($params['page']) ? $params['page'] : 1;
        
        $res = db('collection_content')->field('id,nodeid,status,title,url,inserts')
        ->order('id DESC')->paginate($page, $limit);
        $res['data'] = array_map(function($item) {
            $item['nodeid'] = db('collection_node')->where(['nodeid'=>$item['nodeid']])->value('name');
            $item['title'] = Handle::truncateString($item['title'], 100);
            return $item;
        }, $res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;        
    }
    
    /**
     * 添加爬虫内容到指定栏目
     * @author zhaosong
     * @param array $params 请求参数，包括栏目ID、标题、内容、点击量、来源、排序方式等
     * @return array 返回操作状态和信息
     */
    public static function addlogic(array $params)
    {
        if(empty($params['catid'])) return ['status' => 0, 'msg' => '请选择栏目！'];
        $collection = db('collection_content');
        
        $data = [];
        $data['modelid'] = get_category($params['catid'],'modelid');
        $data['click'] = !empty($params['click']) ? $params['click'] :rand(100,1000);
        $data['copyfrom'] = !empty($params['copyfrom']) ? $params['copyfrom'] :'';
        $order = !empty($params['listorder']) ? 'id DESC':'id ASC';
        $data['auto_thum'] = $params['auto_thum'];
        $data['status'] = 0;
        $data['catid'] = $params['catid'];
        
        foreach ($params['ids'] as $id){
            $res = $collection->order($order)->where(['id' => $id ,'status' => 1])->find();
            if(empty($res)) return ['status' => 00, 'msg' => '未采集不能导入'];
            $data['title'] = $res['title'];
            $data['content'] = $res['content'];
            $data['inputtime'] = $res['inputtime'];
            $collection->where(['id' => $id])->update(['inserts'=>1]);
        }
        
        return C::addlogic($data);
    }
    
    /**
     * 获取栏目列表
     * @author zhaosong
     * @return array 返回栏目列表和状态信息
     */
    public static function catidlogic()
    {
        $list = db('category')->where(['type' => 1])->field('catname,catid,parentid')->order('listorder DESC')->select();
        $lists = Handle::_generateListTree($list, 0, ['catid', 'parentid']);
        return ['status' => 200, 'data' => $lists];
    }
    
    /**
     * 删除爬虫内容
     * @author zhaosong
     * @param string $id 爬虫内容ID，多个ID用逗号分隔
     * @return array 返回操作状态和信息
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return ['status' => 0, 'msg' => '参数错误'];
        db('collection_content')->destroy(explode(',',$id));
        return ['status' => 200, 'msg' => '操作成功'];
    }
    
}