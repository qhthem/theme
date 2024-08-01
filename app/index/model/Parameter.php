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
namespace app\index\model;

class Parameter
{
    /**
     * 处理通用参数
     *
     * @param array $params 参数
     * @author zhaosong
     */
    public static function procecommon(array $params)
    {
        foreach ($params as $k => $v) {
            $time_field = ["inputtime", "updatetime", 'logintime', 'addtime', 'create_times', 'uploadtime', 'creat_time'];
            if (in_array($k, $time_field)) {
                $params[$k] = self::procetime($v);
            }

            $catid_field = ["catid", 'category_id'];
            if (in_array($k, $catid_field)) {
                $params[$k] = self::procecatid($v);
            }

            $thumb_field = ["thumb"];
            if (in_array($k, $thumb_field)) {
                $params[$k] = get_thumb($v);
            }
        }

        return $params;
    }

    /**
     * 处理时间
     *
     * @param int|string $time 时间戳或时间字符串
     * @param bool        $type 是否显示为人类可读的格式
     * @author zhaosong
     */
    public static function procetime($time, bool $type = true)
    {
        return $type ? time_ago($time) : date('Y年n月j日', $time);
    }

    /**
     * 处理分类ID
     *
     * @param int $catid 分类ID
     * @author zhaosong
     */
    public static function procecatid($catid)
    {
        return db('category')->where(['catid' => $catid])->value('catname');
    }
}