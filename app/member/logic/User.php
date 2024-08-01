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

/**
 * User 类
 *
 * @author zhaosong
 */
class User
{
    /**
     * 获取用户信息
     *
     * @param string $userid
     * @return array
     */
    public function get_userinfo($userid = ''): array
    {
        $userid = isset($userid) ? intval($userid) : showmsg('参数错误',false);
        $info = db('member')->where(['userid' => $userid])->find();
        if (!$info) showmsg('会员不存在或已被删除！',false);
        if ($info['status'] != 1) showmsg('会员状态异常！',false);
        return $info;
    }

    /**
     * 首页逻辑
     *
     * @param int $userid
     */
    public function indexlogic(int $userid): void
    {
        $_userid = get_cookie('_userid');
        $info = $this->get_userinfo($userid);
        $is_follow = is_follow(intval($_userid), intval($userid));

        extract($info);

        $model = db('model')->where(['status' => 1])->field('modelid,name')->select();
        $follow_total = db('follow')->where(['userid' => $userid])->count();
        $fans_total = db('follow')->where(['followid' => $userid])->count();

        include view('member', 'home');
    }
}