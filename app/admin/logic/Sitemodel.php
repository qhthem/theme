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
use app\admin\model\Sql;

/**
 * 作者: zhaosong
 * 功能: 模型管理逻辑层
 */
class Sitemodel
{
    /**
     * 作者: zhaosong
     * 功能: 获取模型列表
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function indexlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 20;
        $page = !empty($params['page']) ? $params['page'] : 1;
        
        $res = db('model')->order('modelid DESC')->paginate($page, $limit);
        $res['data'] = array_map(function($item) {
            $item['status'] = $item['status'] ? true :false;
           return $item;
        }, $res['data']);
        
        $data['status'] = 200;
        $data['data'] = $res;
        
        return $data;
    }
    
    /**
     * 作者: zhaosong
     * 功能: 添加模型
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function addlogic(array $params)
    {
        if(!preg_match('/^[a-zA-Z]{1}([a-zA-Z0-9]|[_]){0,29}$/', $params['tablename'])){
            return ['status' => 0, 'msg' => '表名格式不正确'];
        }
        if(db('model')->table_exists($params['tablename'])){
            return ['status' => 0, 'msg' => '表名已存在'];
        }
        
        if(!empty($params['isdefault'])){
			db('model')->update(['isdefault'=>0]);
		}
		
		Sql::createTable($params['tablename']);
		$res = db('model')->insert($params);
		
		return ['status' => $res ? 200 :0, 'msg' => $res ? '添加成功':'添加失败'];
    }
    
    /**
     * 作者: zhaosong
     * 功能: 更新模型
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function updatelogic(array $params)
    {
        if(!preg_match('/^[a-zA-Z]{1}([a-zA-Z0-9]|[_]){0,29}$/', $params['tablename'])){
            return ['status' => 0, 'msg' => '表名格式不正确'];
        }

        if(!empty($params['isdefault'])){
			db('model')->update(['isdefault'=>0]);
		}

        db('model')->where(['modelid' => $params['modelid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 作者: zhaosong
     * 功能: 获取更新模型信息
     * @param int $modelid 模型ID
     * @return array 返回结果
     */
    public static function getUpdateInfoslogic($modelid)
    {
        if (!$modelid) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('model')->where(['modelid' => $modelid])->find();
        $res['isdefault'] = (string) $res['isdefault'];
        $res['status'] = (string) $res['status'];
        return (['status' => 200, 'data' => $res]);
    }
    
    /**
     * 作者: zhaosong
     * 功能: 更新模型状态
     * @param array $params 请求参数
     * @return array 返回结果
     */
    public static function statuslogic(array $params)
    {
        if(empty($params['modelid'])){
            return (['status'=>00,'msg'=>'参数错误']);
        }
        
        db('model')->where(['modelid'=>$params['modelid']])->update($params);
		return (['status'=>200,'msg'=>'操作成功']);
    }
    
    /**
     * 作者: zhaosong
     * 功能: 删除模型
     * @param int $modelid 模型ID
     * @return array 返回结果
     */
    public static function deletelogic($modelid)
    {
        if (!$modelid) return ['status' => 0, 'msg' => '参数错误'];
        foreach (explode(',',$modelid) as $res){
            $tablename = db('model')->where(['modelid' => $res])->value('tablename');
            Sql::deltable($tablename);
            db('model')->where(['modelid' => $res])->delete();
            db('field')->where(['modelid' => $res])->delete();
            db('category')->where(['modelid' => $res])->delete();
        }
        return ['status' => 200, 'msg' => '操作成功'];
    }
}