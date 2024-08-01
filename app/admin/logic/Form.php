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
use app\admin\model\Content as Contentmodel;
class Form
{
    /**
     * 表单验证
     *
     * @param array $params 参数
     * @param int $modelid 模型ID
     * @return array 验证结果
     * @author zhaosong
     */
    public static function FormValidation(array $params, int $modelid)
    {
        $r = db('field')->field('field,name')->where(['isrequired' => 1, 'status' => 1, 'modelid' => $modelid])->select();
        foreach ($r as $v) {
            if (!isset($params[$v['field']])) {
                return ['status' => 00, 'msg' => $v['name'] . '不可以为空！'];
            }
        }
    }

    /**
     * 数组处理
     *
     * @param array $params 参数
     * @return array 处理后的数组
     * @author zhaosong
     */
    public static function Arrayprocessing(array $params)
    {
        foreach ($params as $_k => $_v) {
            $params[$_k] = !is_array($params[$_k]) ? $_v : self::content_dispose($_v);
        }

        return $params;
    }

    /**
     * 处理对象
     *
     * @param mixed $params 参数
     * @author zhaosong
     */
    public static function Processingobjects($params)
    {
    }

    /**
     * 创建表单
     *
     * @param int $modelid 模型ID
     * @return array 表单数据
     * @author zhaosong
     */
    public static function createform($modelid = '')
    {
        $modelid = !empty($modelid) ? $modelid : Contentmodel::getdefaultmodel(0, false);
        $data = db('field')->where(['status' => 1, 'modelid' => $modelid])->select();
        foreach ($data as $k => $v) {
            $data[$k]['setting'] = (array)json_decode($data[$k]['setting']);
            $data[$k]['setting'] = array_map(function ($value) {
                return [
                    "label" => $value,
                    "value" => $value,
                ];
            }, $data[$k]['setting']);
        }

        return ['status' => 200, 'data' => $data];
    }

    /**
     * 表单更新
     *
     * @param string $res 资源
     * @param mixed $value 值
     * @return mixed 处理后的值
     * @author zhaosong
     */
    public static function form_updata($res, $value)
    {
        $r = db('field')->field('fieldtype,field')->where(['field' => $res])->find();
        if (!$r) {
            return $value;
        }
        $typeMap = [
            'switch' => function ($value) {
                return $value ? true : false;
            },
            'select' => function ($value) {
                return strval($value);
            },
            'number' => 'intval',
            'decimal' => 'floatval',
            'datetime' => 'intval',
            'checkbox' => function ($value) {
                return !empty($value) ? explode(',', $value) : [];
            },
            'tag' => function ($value) {
                return !empty($value) ? explode(',', $value) : [];
            },
            'transfer' => function ($value) {
                return !empty($value) ? explode(',', $value) : [];
            },
        ];
        $type = $r['fieldtype'];
        return isset($typeMap[$type]) ? ($typeMap[$type]($value)) : $value;
    }

    /**
     * 内容处理
     *
     * @param array $content 内容
     * @return mixed 处理后的内容
     * @author zhaosong
     */
    private static function content_dispose($content)
    {
        $is_array = false;
        foreach ($content as $val) {
            if (is_array($val)) {
                $is_array = true;
                break;
            }
        }
        if (!$is_array) {
            return implode(',', $content);
        } else {
            $processedContent = [];

            foreach ($content as $val) {
                if (is_array($val)) {
                    $processedContent[] = implode(',', $val);
                } else {
                    $processedContent[] = $val;
                }
            }

            return implode(',', $processedContent);
        }
    }
}