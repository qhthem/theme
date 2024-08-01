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
use app\member\model\Sign;
use app\member\model\Experience as exps;

/**
 * Common 类
 *
 * @author zhaosong
 */
class Common
{
    public $db, $userid, $memberinfo;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->check_member();
        is_group_auth();
    }
 
    /**
     * 检查会员
     */
    private function check_member()
    {
        $this->userid = intval(get_cookie('_userid'));

        if (empty($this->userid)) {
            Request()::isPost() ? json(['msg' => '请先登录!', 'status' => 0]) : showmsg("请先登录!", url('index/index/index'),false);
        }

        $this->db = db('member');
        $userinfo = cache_get_or_set('userinfo_key'.$this->userid, function(){
            return db('member')->where(['userid' => $this->userid])->find();
        }, 3600);
        
        $signinfo = (new Sign())->_get_sign_info();
        $totalinfo = $this->total_memberinfo();
        $user_url = ['user_url' => '/user/' . $this->userid];

        if(!empty($totalinfo)){
            $this->memberinfo = array_merge($userinfo, $signinfo, $totalinfo, $user_url);
        }
        else {
            showmsg('拉取数据中...', url('member/index/index'));
        }

        if (empty($this->memberinfo) || $this->memberinfo['status'] == '0') {
            showmsg('账号异常！',false);
        }
        
        $this->is_bind_mail($userinfo);
        exps::_check_update_group(get_cookie('_userid'),$userinfo['experience'],$userinfo['groupid'], 0);
    }

    /**
     * 统计会员信息
     *
     * @return array
     */
    private function total_memberinfo()
    {
        $userid = intval(get_cookie('_userid'));
        
        $total_memberinfo = Cache()->get('total_memberinfo_'.$userid);
        if(empty($total_memberinfo)){
            $content_total = db('content')->where(['userid' => $userid])->count();
            $comment_total = db('comments')->where(['userid' => $userid])->count();
            $favorite_total = db('favorite')->where(['userid' => $userid])->count();
            $follow_total = db('follow')->where(['userid' => $userid])->count();
            $fans_total = db('follow')->where(['followid' => $userid])->count();
            $message_total = db('message')->where(['send_to' => $userid,'isread' => 0])->count();
    
            $data = [
                'content_total' => $content_total,
                'comment_total' => $comment_total,
                'favorite_total' => $favorite_total,
                'follow_total' => $follow_total,
                'fans_total' => $fans_total,
                'message_total' => $message_total,
            ];
            
            Cache()->set('total_memberinfo_'.$userid,$data,3600);
            
        }

        return $total_memberinfo;
    }
    
    /**
     * 检查用户是否绑定了邮箱并验证
     *
     * @author zhaosong
     * @return void
     */
    private function is_bind_mail(array $userinfo)
    {
        if(GetRoute('action') == 'bind_mail' && GetRoute('app') == 'member' && GetRoute('controller') == 'index'){
           return false;
        }
        if (empty($userinfo['email'])) {
            showmsg('你没有绑定邮箱！', url('member/index/bind_mail'),false);
        }
        if (empty($userinfo['email_status'])) {
            showmsg('你绑定的邮箱没有验证！', url('member/index/bind_mail'),false);
        }
    }
}