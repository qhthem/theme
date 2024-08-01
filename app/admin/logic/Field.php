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
use app\admin\model\Handle;

class Field 
{
    /**
     * 获取字段列表
     *
     * @param array $params 参数数组
     * @return array 返回数组
     */
    public static function indexlogic(array $params)
    {
        $Traverse = Traverse();
        $limit  = $Traverse->empty($params, 'limit', 10);
        $page = $Traverse->empty($params, 'page', 1);
        
        $where = [];
        $where['modelid'] = !empty($params['modelid']) ? self::modellogic(['tablename' => $params['modelid']], 'modelid') : null;
        $where['name'] = !empty($params['name']) ? $params['name'] : null;
        $where['fieldtype'] = !empty($params['fieldtype']) ? $params['fieldtype'] : null;

        $res = db('field')->where($where)->order('listorder DESC')->paginate($page, $limit);
        $res['data'] = $Traverse->factory($res['data']);

        $data['status'] = 200;
        $data['modelid'] = self::getFieldListlogic();
        $data['data'] = $res;

        return $data;
    }

    /**
     * 添加字段
     *
     * @param array $params 参数数组
     * @return array 返回数组
     */
    public static function addlogic(array $params)
    {
        $errors = [];
        $requiredFields = ['field', 'name', 'modelid', 'fieldtype'];
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                $errors[] = ucfirst($field) . '不能为空！';
            }
        }

        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{0,29}$/', $params['field'])) {
            $errors[] = '字段格式不正确';
        }

        if (!empty($errors)) {
            return ['status' => 0, 'msg' => $errors[0]];
        }

        if (!Sql::checkField($params['modelid'], $params['field'])) {
            return ['status' => 0, 'msg' => '字段在表中已经存在'];
        }

        $fieldType = $params['fieldtype'];

        if (in_array($fieldType, ['select', 'radio', 'checkbox','chuansuok','popselect'])) {
            $params['setting'] = Handle::array2string(explode('|', rtrim($params['setting'], '|')));
        } else {
            unset($params['setting']);
        }

        $fieldTypeHandlers = [
            'input' => 'addVarCharField',
            'datetime' => 'addVarCharField',
            'textarea' => 'addMediumTextField',
            'images' => 'addMediumTextField',
            'attachments' => 'addMediumTextField',
            'number' => 'addIntField',
            'decimal' => 'addDoubleField',
            'rate' => 'addIntField',
            'slider' => 'addIntField',
            'switch' => 'addBooleanField'
        ];

        $handler = $fieldTypeHandlers[$fieldType] ?? 'addVarCharField';

        Sql::$handler($params['modelid'], $params['field'], $params['maxlength'] ?? null);
        $params['modelid'] = self::modellogic(['tablename' => $params['modelid']], 'modelid');

        db('field')->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
    }
    
    /**
     * 获取字段列表
     *
     * @param array $params
     * @return array
     */
    public static function getFieldListlogic()
    {
        $model = db('model')->field('tablename,name')->order('modelid DESC')->select();
        foreach ($model as $k => $res) {
            $model[$k]['label'] = $model[$k]['name'];
            $model[$k]['value'] = $model[$k]['tablename'];
            unset($model[$k]['name'], $model[$k]['tablename']);
        }
        return ['status' => 200, 'data' => $model];
    }
    
    /**
     * 更新字段状态
     *
     * @param array $params
     * @return array
     */
    public static function statuslogic(array $params)
    {
        if (empty($params['fieldid'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
    
        db('field')->where(['fieldid' => $params['fieldid']])->update($params);
        return ['status' => 200, 'msg' => '操作成功'];
    }
    
    /**
     * 获取更新信息
     *
     * @param integer $fieldid
     * @return array
     */
    public static function getUpdateInfoslogic($fieldid)
    {
        if (!$fieldid) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('field')->where(['fieldid' => $fieldid])->find();
        $res['isrequired'] = (string) $res['isrequired'];
        $res['status'] = (string) $res['status'];
        $res['modelid'] = self::modellogic(['modelid' => $res['modelid']], 'tablename');
        if (!empty($res['setting'])) {
            $res['setting'] = join('|', Handle::string2array($res['setting']));
        }
        return ['status' => 200, 'data' => $res];
    }
    
    /**
     * 更新字段逻辑
     *
     * @author zhaosong
     * @param array $params
     * @return array
     */
    public static function updatelogic(array $params)
    {
        if (empty($params['name'])) {
            return ['status' => 0, 'msg' => '字段名称不可以为空'];
        }
        $params['modelid'] = self::modellogic(['tablename' => $params['modelid']], 'modelid');
    
        $fieldType = $params['fieldtype'];
    
        if (in_array($fieldType, ['select', 'radio', 'checkbox'])) {
            $params['setting'] = Handle::array2string(explode('|', rtrim($params['setting'], '|')));
        } else {
            unset($params['setting']);
        }
    
        db('field')->where(['fieldid' => $params['fieldid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 删除字段逻辑
     *
     * @author zhaosong
     * @param string $fieldid
     * @return array
     */
    public static function deletelogic($fieldid)
    {
        if (!$fieldid) return ['status' => 0, 'msg' => '参数错误'];
        foreach (explode(',', $fieldid) as $res) {
            $e = db('field')->field('modelid,field')->where(['fieldid' => $res])->find();
            $tablename = self::modellogic(['modelid' => $e['modelid']], 'tablename');
            Sql::delColumn($tablename, $e['field']);
            db('field')->where(['fieldid' => $res])->delete();
        }
        return ['status' => 200, 'msg' => '操作成功'];
    }
    
    /**
     * 获取模型信息
     *
     * @author zhaosong
     * @param array $param
     * @param string $field
     * @return mixed
     */
    public static function modellogic($param, $field)
    {
        return get_model_where($param, $field);
    }
    
}