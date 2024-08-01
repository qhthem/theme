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

class Index
{
    /**
     * 获取菜单
     *
     * @return array 菜单数据
     */
    public static function menulogic()
    {
        $roleid = Session()::get('admininfo')['roleid'];
        $db = C('db_prefix').'admin_role_priv b '; 
        
        $a = db('menu')->where(['status'=>1,'roleid'=>$roleid])->alias('a')
         ->join($db,'b.typeid= a.id')->order('sort desc')->select();
        $b = db('menu')->order('sort desc')->where(['status'=>1])->select();
        
        $res = $roleid == 1 ? $b : $a;
        
        foreach ($res as $k => $v) {
			$res[$k]['key'] = '1-'.$res[$k]['id'];
			$res[$k]['label'] = $res[$k]['name'];
            $res[$k]['link'] = '/'.$res[$k]['m'].'/'.$res[$k]['c'].'/'.$res[$k]['a'].'';
		}
		
		$data['data'] = Handle::_generateListTree($res,0,['id','parentid']);
        $data['status'] = 200;
        
        return $data;
    }
    
    /**
     * 根据传入的参数生成面包屑导航
     *
     * @param array $params 参数数组，包含一个名为 'data' 的元素
     * @return array 包含状态、消息和数据的数组
     */
    public static function Breadcrumblogic(array $params)
    {
        if (Request()::isPost()){
            if($params['data'] == 'mian') return (['status'=>200,'msg'=>'成功','data'=>['d'=>'工作台','t'=>'概况']]);

            $d = db('menu')->where(['data'=>$params['data']])->field('id,name,parentid')->find();
            if($d){
                $t = db('menu')->where(['id'=> $d['parentid']])->field('id,name,parentid')->find();
                return (['status'=>200,'msg'=>'成功','data'=>['t'=>$d['name'],'d'=>$t['name']]]);
            }
        }

        return ['status' => 400, 'msg' => '请求方法不是 POST'];
    }  
    
}