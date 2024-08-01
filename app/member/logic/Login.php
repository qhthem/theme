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
use extend\verify\Validator;
use app\member\model\Finance as F;
use extend\qq\Qq as qqapi;
use extend\UserSig\TLSSigAPIv2;


/**
 * Login 类
 *
 * @author zhaosong
 */
class Login
{
    /**
     * 登录逻辑
     *
     * @param array $param
     * @return array
     */
    public static function loginlogic(array $param)
    {
        if (empty($param['username']) || empty($param['password'])) {
            return ['status' => 0, 'msg' => '用户名或者密码不能为空！'];
        }
        $member = db('member');

        $username = trim($param['username']);
        $password = md5s($param['password']);
        $where = Validator::isEmail($username) ? ['email' => $username] : ['username' => $username];

        $data = $member->where($where)->find();

        if (!$data || ($data['password'] != $password)) {
            return ['status' => 0, 'msg' => '密码错误或者用户名不正确'];
        }

        if ($data['status'] == '0') {
            return ['status' => 0, 'msg' => '用户状态错误'];
        }

        $Session = [
            '_userid' => $data['userid'],
            '_username' => $data['username'],
            '_groupid' => $data['groupid'],
            '_nickname' => $data['nickname'],
            '_vip' => $data['vip'],
        ];

        add_del_userinfo_cookie($data, true, false);

        Session()::set('userinfo_' . $data['userid'], $Session);

        $last_day = date('d', $data['lastdate']);
        if ($last_day != date('d') && time() > $data['lastdate'] && C('login_point') > 0) {
            F::add(C('login_point'), $data['userid'], $data['username'], '积分', '每日登录', '自动获取');
        }

        $where = [];
        if ($data['vip'] && $data['overduedate']< time()) {
            $where['vip'] = 0;
        }
        $where['ip'] = getip();
        $where['lastdate'] = time();
        $member->where(['userid' => $data['userid']])->setInc('loginnum');
        $member->where(['userid' => $data['userid']])->update($where);
        
        if(C('app_TLSSigAPIv2')){
            $api = new TLSSigAPIv2(C('app_sdkappid'), C('app_sdksecretkey'));
            $sig = $api->genUserSig($username);
        }
        $meminfo = ['nickname' => get_userinfo($data['userid'],'nickname') ,'userpic'=>get_Domain().get_avatar($data['userid'])];
        return ['status' => 200, 'msg' => '登录成功！', 'url' => '/member' ,'UserSig'=>empty($sig) ? false :$sig ,'user'=>$meminfo];
    }

    /**
     * 注册逻辑
     *
     * @param array $param
     * @return array
     */
    public static function registerlogic(array $param)
    {
        if (empty(C('member_register'))) {
            return ['status' => 0, 'msg' => '管理员关闭了新会员注册'];
        }

        if (empty($param['username']) || empty($param['password'])) {
            return ['status' => 0, 'msg' => '用户名或者密码不能为空！'];
        }

        $member = db('member');

        if (Validator::isEmail($param['username'])) {
            return ['status' => 0, 'msg' => '邮箱格式不正确！'];
        }

        $result = $member->field('userid')->where(['username' => $param['username']])->find();
        if ($result) return ['status' => 0, 'msg' => '该用户名已注册！'];

        $result = $member->field('userid')->where(['email' => $param['email']])->find();
        if ($result) return ['status' => 0, 'msg' => '该邮箱已注册！'];

        $data['nickname'] = $data['username'] = $param['username'];
        $data["password"] = md5s($param['password']);
        $data['lastdate'] = $data['create_time'] = time();
        $data['ip'] = getip();
        $data['groupid'] = '1';
        $data['email'] = $param['email'];
        $data['amount'] = '0.00';
        $data['point'] = $data['experience'] = C('member_point');
        $data['status'] = C('member_check');

        $userid = $member->insertGetId($data);

        $Session = [
            '_userid' => $userid,
            '_username' => $data['username'],
            '_groupid' => $data['groupid'],
            '_nickname' => $data['nickname'],
            '_vip' => 0,
        ];

        add_del_userinfo_cookie($data, true, false);
        Session()::set('userinfo_' . $userid, $Session);

        return ['status' => 200, 'msg' => '注册成功！', 'url' => '/member'];
    }
    
    
    /**
     * QQ登录逻辑
     *
     * @author zhaosong
     */
    public static function qq_loginlogic()
    {
        if (!isset($_GET['code'])) {
            return header("Location: " . (new qqapi())->redirect_to_login());
        } else {
            $code = $_GET['code'];
            $openid_array = (new qqapi())->get_openid($code);
            
            if(empty($openid_array['openid']) && $openid_array['access_token']){
                showmsg("获取openid或token失败！",false);
            }
            
            // 跳转绑定
            if(get_cookie('_bind') == 'qq'){
                showmsg("正在绑定！",url('/member/api/bind',['type'=>'qq','openid'=>$openid_array['openid']]));
            }
            
            $res = (new qqapi())->get_user_info($openid_array['access_token'],$openid_array['openid']);
            
            $re = db('member')->where(['openid' => $openid_array['access_token']])->find();
            // 如果没有找到，进行注册
            if (empty($re)) {
                $data = [
                    'openid' => $openid_array['access_token'],
                    'nickname' => $res['nickname'],
                    'image' => $res['figureurl_qq_2'],
                    'sex' => $res['gender'] == "男" ? 0:1,
                    'year' => $res['year'],
                    'province' => $res['province'],
                    'city' => $res['city'],
                ];
    
                self::login_qq($data);
    
            } else {
                self::login_qq();
            }
        }
    }
    
    /**
     * QQ登录拉取信息
     *
     * @param array $array 用户数据
     * @author zhaosong
     */
    public static function login_qq(array $array = [])
    {
        $openid = Session()::get('openid');
        $data = db('member')->where(['openid' => $openid])->find();
        if (empty($data)) {
            $sex = get_cookie('sex') == "男" ? 0:1;
            $arr = [
                'openid' => $openid,
                'nickname' => $array['nickname'],
                'userpic' => $array['image'],
                'username' => 'u_' . random() . '',
                'sex' => $sex,
                'password' => md5s('123456'),
                'groupid' => '1',
                'amount' => '0.00',
                'ip' => getip(),
                'create_time' => time(),
                'area' => '北京市|市辖区|东城区',
                'status' => 1,
                'point' => C('member_point'),
                'experience' => C('member_point'),
            ];
            // 插入默认数据    
            db('member')->insertGetId($arr);
            $data = db('member')->where(['openid' => $openid])->find();
            add_del_userinfo_cookie($data, true, false);
            showmsg("登录成功，正在跳转!", url('member/index/index'));
        } else {
            add_del_userinfo_cookie($data, true, false);
            showmsg("登录成功，正在跳转!", url('member/index/index'));
        }
    }
    
    /**
     * 获取用户信息
     *
     * @param array $array 包含用户相关信息的数组，默认为空数组
     * @return array 返回包含状态码和数据的数组
     * @author zhaosong
     */
    public static function get_userinfos()
    {
        $data = db('member')
        ->where(['email_status'=>1,'status'=>1])->select();
        if(empty($data)) return ['status' => 200, 'sg' => '获取信息失败'];
        foreach ($data as $k =>$v){
            if(empty($data[$k]['userpic'])){
                if (!self::startsWithHttps($data[$k]['userpic'])){
                    $data[$k]['userpic'] = get_Domain().get_avatar($data[$k]['userid']);
                }
            }
            else{
                if (!self::startsWithHttps($data[$k]['userpic'])){
                    $data[$k]['userpic'] = get_Domain().get_avatar($data[$k]['userid']);
                }
            }
        }
    
        return ['status' => 200, 'data' => $data];
    }
    
    
    public static function startsWithHttps($string) {
        $http = 'http';
        $httpLength = strlen($http);
        $stringStart = strtolower(substr($string, 0, $httpLength));
    
        return $stringStart === $http || $stringStart === 'https';
    }
    

    /**
     * 退出逻辑
     */
    public static function exitlogic()
    {
        Session()::delete('userinfo_' . get_cookie('_userid'));
        add_del_userinfo_cookie([], false, true);
        Session()::delete('total_memberinfo');
        showmsg("退出成功!", '/');
    }
}