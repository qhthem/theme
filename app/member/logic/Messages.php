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
namespace app\member\logic;
use app\member\logic\Common;
use app\member\model\Html;

/**
 * Messages 类
 *
 * @author zhaosong
 */
class Messages extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页逻辑
     */
    public function indexlogic(): void
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'messages');
    }

    /**
     * 获取用户头像
     *
     * @param string $type
     * @return string
     */
    protected function get_userpic($type = '', $userid = ''): string
    {
        $_userid = get_cookie('_userid');
        if (empty($type)) {
            return get_avatar($userid);
        } else {
            switch ($type) {
                case 'system':
                    return '/static/them/images/消息通知.png';
                case 'follow':
                    return '/static/them/images/关注通知.png';
                case 'like':
                    return '/static/them/images/点赞通知.png';
                case 'interact':
                    return '/static/them/images/互动通知.png';
                case 'wallet':
                    return '/static/them/images/我的钱包.png';
                default:
                    return '';
            }
        }
    }

    /**
     * 获取昵称
     *
     * @param string $type
     * @param string $userid
     * @return string
     */
    public function get_nickname($type = '', $userid = ''): string
    {
        if (empty($type)) {
            return get_userinfo(intval($userid), 'nickname');
        } else {
            switch ($type) {
                case 'system':
                    return '系统通知';
                case 'follow':
                    return '关注通知';
                case 'like':
                    return '点赞通知';
                case 'interact':
                    return '互动通知';
                case 'wallet':
                    return '钱包通知';
                default:
                    return '';
            }
        }
    }

    /**
     * 列表逻辑
     *
     * @return array
     */
    public function listlogic(): array
    {
        $userid = get_cookie('_userid');
        $data = db('message')->where(['send_to' => $userid])
            ->field('time,content,type,send_from,id')
            ->order('id DESC')->select();

        $temp = [];
        $result = [];

        foreach ($data as $item) {
            $item['time'] = date("m-d", $item['time']);
            $item['userpic'] = $this->get_userpic($item['type'], $item['send_from']);
            $item['nickname'] = $this->get_nickname($item['type'], $item['send_from']);
            $item['isread'] = db('message')->where(['id'=>$item['id'],'send_to' => $userid,'isread' => 0])->count();
            if (!isset($temp[$item['type']])) {
                $temp[$item['type']] = true;
                $result[] = $item;
            }
        }

        return ['data' => $result, 'status' => 200];
    }

    /**
     * 多态方法
     *
     * @param array $parame
     * @return array
     */
    public function polymerize(array $parame)
    {
        if (!empty($parame['type'])) {
            return $this->pagelogic($parame);
        } else {
            return $this->pageslogic($parame);
        }
    }

    /**
     * 分页逻辑
     *
     * @param array $parame
     * @return array
     */
    public function pagelogic(array $parame): array
    {
        $userid = get_cookie('_userid');
        $data = db('message')->where(['send_to' => $userid, 'type' => $parame['type']])
            ->field('time,content,type,send_from,id')
            ->order('id DESC')->select();
        $data = array_map(function ($item) {
            $item['time'] = date("Y-m-d", $item['time']);
            $item['userpic'] = $this->get_userpic($item['type']);
            $item['nickname'] = $this->get_nickname($item['type'], $item['send_from']);
            return $item;
        }, $data);

        $result = Html::page_default_message($data, $parame['type']);

        return ['data' => $result, 'status' => 200];
    }

    /**
     * 页面逻辑
     *
     * @param array $parame
     * @return array
     */
    public function pageslogic(array $parame): array
    {
        $userid = get_cookie('_userid');
        db('message')->where(['send_from' => $parame['send_from'], 'isread' => 0])->update(['isread' => 1]);
        
        $data = db('message')->query("SELECT * FROM `qh_message` WHERE `send_from` = '{$parame['send_from']}' AND `send_to` = '$userid' OR `send_from` = '$userid' AND `send_to` = '{$parame['send_from']}' ORDER BY id ASC;");

        $data = array_map(function ($item) {
            $item['time'] = date("Y-m-d", $item['time']);
            $item['userpic'] = $this->get_userpic($item['type'], $item['send_from']);
            $item['nickname'] = $this->get_nickname($item['type'], $item['send_from']);
            return $item;
        }, $data);

        $result = Html::page_userid_message($data, $parame['send_from']);

        return ['data' => $result, 'status' => 200];
    }

    /**
     * 添加逻辑
     *
     * @param array $params
     * @return array
     */
    public function addlogic(array $params): array
    {
        if (empty(get_cookie('_userid'))) {
            return ['msg' => '请先登录！', 'status' => 00];
        }
        
        if (empty($params['send_to'])) {
            return ['msg' => '发送对象获取失败', 'status' => 00];
        }
        
        if ($params['send_to'] == get_cookie('_userid')) {
            return ['msg' => '自己不能给自己发送消息！', 'status' => 00];
        }

        if (empty($params['content'])) {
            return ['msg' => '内容不能为空', 'status' => 00];
        }

        $result = [
            'send_from' => get_cookie('_userid'),
            'send_to' => $params['send_to'],
            'time' => time(),
            'content' => $params['content'],
            'status' => 1,
            'isread' => 0
        ];

        $data = db('message')->insertGetId($result);
        return ['msg' => $data ? '发送成功' : '发送失败', 'status' => 200];
    }
}