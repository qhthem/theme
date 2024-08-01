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
namespace app\member\model;
/**
 * Msg 类
 *
 * @author zhaosong
 */
class Msg
{
    /**
     * 发送消息
     *
     * @param string $type
     * @param int $from
     * @param int $to
     * @param string $content
     * @return bool
     */
    public static function send_msg($type, $from, $to, $content): bool
    {
        $result = [
            'send_from' => $from,
            'send_to' => $to,
            'time' => time(),
            'content' => $content,
            'status' => 1,
            'isread' => 0,
            'type' => $type
        ];

        $data = db('message')->insertGetId($result);

        return $data ? true : false;
    }
}