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
namespace app\index\logic;
use app\member\model\Finance;
use app\member\model\Experience as exps;
use app\index\model\Parameter;
use app\member\model\Other;

class Comment
{
    /**
     * 获取评论列表
     *
     * @param array $params 参数
     * @author zhaosong
     */
    public static function comment_list(array $params)
    {
        $page = !empty($params['page']) ? $params['page'] : 1;
        $limit = !empty($params['limit']) ? $params['limit'] : 6;
        $field = !empty($params['field']) ? $params['field'] : '*';
        $order = !empty($params['order']) ? $params['order'] : 'creat_time DESC';
        $where = [];
        $where['status'] = 1;
        $where['modelid'] = $params['modelid'];
        $where['aid'] = $params['sid'];
        $where['parentid'] = 0;

        $res = db('comments')->field($field)->where($where)->order($order)->paginate($page, $limit);
        $res['data'] = array_map(function ($item) {
            $item = Parameter::procecommon($item);
            $item['userpic'] = get_avatar($item['userid']);
            $item['nickname'] = get_userinfo($item['userid'], 'nickname');
            $item['lv'] = get_userlv($item['userid']);
            $item['user_url'] = '/user/' . $item['userid'];
            $item['children'] = self::paren_children($item['id']);
            return $item;
        }, $res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        $data['total'] = $res['total'];
        return $data;
    }

    /**
     * 获取子评论
     *
     * @param int $id 评论ID
     * @author zhaosong
     */
    protected static function paren_children(int $id)
    {
        $result = db('comments')->where(['parentid' => $id])->order('creat_time DESC')->select();
        $result = array_map(function ($item) {
            $item = Parameter::procecommon($item);
            $item['userpic'] = get_avatar($item['userid']);
            $item['nickname'] = get_userinfo($item['userid'], 'nickname');
            $item['lv'] = get_userlv($item['userid']);
            $item['user_url'] = '/user/' . $item['userid'];

            $item['reply_name'] = get_userinfo($item['replyid'], 'nickname');
            $item['reply_url'] = '/user/' . $item['replyid'];

            $item['children'] = self::paren_children($item['id']);
            return $item;
        }, $result);
        return $result;
    }

    /**
     * 评论逻辑处理
     *
     * @param array $params 参数
     * @author zhaosong
     */
    public static function commentlogic(array $params)
    {
        $userid = get_cookie('_userid');
        $username = is_userinfo();

        if (empty($userid)) {
            return ['msg' => '请登录！', 'status' => 0];
        }

        if (empty($params['textarea'])) {
            return ['msg' => '内容不能为空！', 'status' => 0];
        }
        
        if (empty($params['aid'])) {
            return ['msg' => '获取文章参数失败！', 'status' => 0];
        }

        $data = [
            'userid' => $userid,
            'replyid' => empty($params['replyid']) ? 0 : $params['replyid'],
            'aid' => $params['aid'],
            'modelid' => $params['modelid'],
            'parentid' => empty($params['parentid']) ? 0 : $params['parentid'],
            'content' => $params['textarea'],
            'creat_time' => time(),
            'ip' => getip(),
            'status' => 1
        ];

        self::_check_auth($userid, $username['_username'], getip());
        self::_check_Mail($userid, $params['textarea'], $data['replyid']);
        $ta = db('comments')->insert($data);
        return ['msg' => $ta ? '评论成功！' : '评论失败！', 'status' => $ta ? 200 : 000];
    }
    
    /**
     * 检查邮件并发送通知
     *
     * @param int    $userid   用户ID
     * @param string $email    收件人邮箱地址
     * @param string $textarea 评论内容
     * @param string $replyid  回复的评论ID
     * @author zhaosong
     */
    protected static function _check_Mail(int $userid,string $textarea, int $replyid = 0)
    {
        if ($userid !== 1 && empty($replyid)) {
            $is = Other::sendMail(C('Username_qq'), 'Astro-系统：有人评论了您的文章', '有人评论了您的文章，评论内容：' . $textarea,true);
            return true;
        }
    
        if ($replyid) {
            $email = get_userinfo($replyid, 'email');
            if (empty($email)) {
                return json(['msg' => '你需要去会员中心验证您的邮箱！', 'status' => 0]);
            }
            $is = Other::sendMail($email, 'Astro-系统：有人回复了您的评论', '有人回复了您的评论，评论内容：' . $textarea,true);
            return true;
        }
    }

    /**
     * 检查权限
     *
     * @param int    $userid   用户ID
     * @param string $username 用户名
     * @param string $ip       IP地址
     * @author zhaosong
     */
    protected static function _check_auth(int $userid, string $username, string $ip)
    {
        $blacklist_ip = site('blacklist_ip');
        if ($blacklist_ip) {
            $arr = explode(',', $blacklist_ip);
            foreach ($arr as $val) {
                if (check_ip_matching($val, $ip)) return ['msg' => 'IP黑名单用户，禁止评论！', 'status' => 0];
            }
        }

        $inputtime = db('comments')->where(['userid' => $userid])->order('id DESC')->value('creat_time');
        if ($inputtime && ($inputtime + 5 >= time())) return ['msg' => '评论过快，请稍后再试！', 'status' => 0];

        $comment_point = site('comment_point');

        $where = [];
        $where['userid'] = $userid;
        $where['type'] = 0;
        $where['creat_time'] = ['>', strtotime(date('Y-m-d'))];

        $total = db('pay')->where($where)->count();

        if ($comment_point > 0 && $total < 5) {
            Finance::add($comment_point, $userid, $username, '积分', '评论文章', '');
            exps::_check_update_exp('comment');
        }
    }
}