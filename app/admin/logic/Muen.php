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
class Muen
{
    /**
     * 获取菜单列表。
     *
     * @return array 菜单列表
     */
    public static function indexlogic()
    {
        $field = 'id,parentid,m,c,status,icon,a,data,sort,name';
        $res = cache_get_or_set('Muen_key', function() use ($field) {
            return db('menu')->field($field)->order('sort DESC')->select();
        }, 7200);
        
		foreach ($res as $k => $v) {
			$res[$k]['link'] = ''.$res[$k]['m'].'/'.$res[$k]['c'].'/'.$res[$k]['a'].'';
			$res[$k]['update'] = '/admin/muen/update/id/'.$res[$k]['id'];
			$res[$k]['status'] = $res[$k]['status'] ? true :false;
		}
		$res['data'] = Handle::_generateListTree($res,0,['id','parentid']);
		$data['status'] = 200;
		$data['data'] = $res['data'];
		return $data;
    }
    
    /**
     * 更新菜单状态。
     *
     * @param array $params 参数，包含菜单 ID
     * @return array 更新结果
     */
    public static function statuslogic(array $params)
    {
        if(empty($params['id'])){
            return (['status'=>00,'msg'=>'参数错误']);
        }
        
        db('menu')->where(['id'=>$params['id']])->update($params);
        Cache()->del('Muen_key');
		return (['status'=>200,'msg'=>'操作成功']);
    }
    
    /**
     * 获取菜单更新信息。
     *
     * @param int $id 菜单 ID
     * @return array 菜单更新信息
     */
    public static function getUpdateInfoslogic($id)
    {
        if(empty($id)){
            return (['status'=>00,'msg'=>'参数错误']);
        }
        $Field = 'id,parentid,m,c,status,icon,a,data,sort,name';
		$res = db('menu')->where(['id'=>$id])->field($Field)->find();
		$res['status'] = 1 ? true :false;
		$res['parentid'] = $res['parentid'] ? $res['parentid'] : strval($res['parentid']);
		return (['status'=>200,'data'=>$res]);
    }
    
    /**
     * 获取菜单更新详细信息。
     *
     * @param array $params 参数，包含菜单 ID 等
     * @return array 更新结果
     */
    public static function getUpdateInfologic(array $params)
    {
        validatePostValues('name,m,c,a',$params);
        db('menu')->where(['id'=>$params['id']])->update($params);
        return (['status'=>200,'msg'=>'修改成功']);
    }
    
    /**
     * 获取菜单字段列表。
     *
     * @param int $id 菜单 ID
     * @return array 菜单字段列表
     */
    public static function getFieldListlogic($id)
    {
        $list = db('menu')->field('name,id,parentid')->order('sort DESC')->select();
		$lists = Handle::_generateListTree($list,0,['id','parentid']);
		return (['status'=>200,'data'=>$lists]);
    }
    
    /**
     * 删除菜单。
     *
     * @param int $id 菜单 ID
     * @return array 删除结果
     */
    public static function deletelogic($id)
    {
        if(empty($id)){
            return (['status'=>00,'msg'=>'参数错误']);
        }
        $list = db('menu')->where(['id'=>$id])->delete();
        Cache()->del('Muen_key');
		return ['status'=>200,'msg'=>'操作成功'];
    }    
    
    /**
     * 添加菜单。
     *
     * @param array $params 菜单参数
     * @return array 添加结果
     */
    public static function addlogic(array $params)
    {
        validatePostValues('name,m,c,a',$params);
        db('menu')->insert($params);
        Cache()->del('Muen_key');
        
        return (['status'=>200,'msg'=>'添加成功']);
    }
    
}