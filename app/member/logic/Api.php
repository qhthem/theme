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
use app\admin\model\Handle;
use app\member\model\Finance as buy;
use app\member\model\Other;
use app\member\model\Alipay;

/**
 * Api 类
 *
 * @author zhaosong
 */
class Api
{
    /**
     * 记录逻辑
     *
     * @param string $type
     * @return array
     */
    public static function recordlogic($type): array
    {
        $data = cache_get_or_set('api_pay_'.$type, function() use ($type) {
            return db('pay')
            ->field('creat_time,msg,money,paytype,userid,type,trade_sn')
            ->where(['type' => $type, 'userid' => get_cookie('_userid')])
            ->order('id DESC')
            ->limit(4)->select();
        }, 600);
        
        $data = array_map(function ($item) {
            $item['creat_time'] = date("Y-m-d H:i:s", $item['creat_time']);
            return $item;
        }, $data);
        
        return ['data' => $data, 'status' => 200];
    }

    /**
     * 成长逻辑
     *
     * @param string $type
     * @return array
     */
    public static function growthlogic($type): array
    {
        if ($type) {
            $data = db('experience')
                ->where(['userid' => get_cookie('_userid')])
                ->order('id DESC')
                ->limit(10)->select();
            $data = array_map(function ($item) {
                $item['creat_time'] = date("Y-m-d H:i:s", $item['creat_time']);
                return $item;
            }, $data);
        } else {
            $data = cache_get_or_set('exp_key', function() {
                return db('exp')->select();
            }, 3600);
        }

        return ['data' => $data, 'status' => 200];
    }

    /**
     * 充值逻辑
     *
     * @param string $type
     * @return array
     */
    public static function Rechargelogic($type): array
    {
        $rechargeData = [
            'R_moeny' => [
                ['label' => 1, 'value' => 1],
                ['label' => 6, 'value' => 6],
                ['label' => 30, 'value' => 30],
                ['label' => 98, 'value' => 97],
                ['label' => 198, 'value' => 195],
            ],
            'R_point' => [
                ['label' => 60, 'value' => 6],
                ['label' => 300, 'value' => 27],
                ['label' => 980, 'value' => 79],
            ],
        ];

        if (!isset($rechargeData[$type])) {
            return ['data' => [], 'status' => 400];
        }

        $data = $rechargeData[$type];

        // 计算折扣
        foreach ($data as &$item) {
            $discount = ($item['label'] - $item['value']) / $item['label'] * 100;
            $item['discount'] = round($discount, 2);
        }

        return ['data' => $data, 'status' => 200];
    }

    /**
     * 设置逻辑
     *
     * @param array $parame
     * @return array
     */
    public static function settinglogic($parame = ''): array
    {
        $db = db('member');
        $userid = get_cookie('_userid');
        if ($parame) {
            $db->where(['userid' => $userid])->update([$parame['field'] => $parame['value']]);
        }

        $data = $db
            ->field('nickname,sex,motto,qq,phone')
            ->where(['userid' => $userid])
            ->find();

        return ['data' => $data, 'status' => 200];
    }

    /**
     * 用户头像逻辑
     *
     * @param string $image
     * @return array
     */
    public static function userpiclogic($image): array
    {
        $db = db('member');
        $userid = get_cookie('_userid');

        if (empty($image)) {
            return ['msg' => '未上传图片', 'status' => 0];
        }
        $upload = Uploads()->upload($image);
        $data = [
            'app' => 'member',
            'userid' => $userid,
            'username' => get_cookie('_username'),
            'uploadip' => getip()
        ];
        $uploaddata = array_merge($upload, $data);
        $r = db('upload')->insert($uploaddata);

        $da = $db->where(['userid' => $userid])->update(['userpic' => $uploaddata['originname']]);

        return ['msg' => $da ? '上传头像成功' : '上传头像失败', 'status' => $da ? 200 : 000];
    }

    /**
     * 分类目录逻辑
     *
     * @return array
     */
    public static function catidlogic(): array
    {
        $list = db('category')->where(['publish' => 1])
            ->field('catname,catid,parentid')->order('listorder DESC')->select();
        $lists = Handle::_generateListTree($list, 0, ['catid', 'parentid']);
        $data = $lists;
        return ['status' => 200, 'data' => $data];
    }

    /**
     * 上传逻辑
     *
     * @param string $image
     * @return array
     */
    public static function uploadlogic($image): array
    {
        $userid = get_cookie('_userid');

        if (empty($image)) {
            return ['msg' => '未上传图片', 'status' => 0];
        }
        $upload = Uploads()->upload($image);
        if (!empty($upload['msg'])) {
            return $upload;
        } else {
            $data = [
                'app' => 'member',
                'userid' => $userid,
                'username' => Session()::get('userinfo_' . $userid)['_username'],
                'uploadip' => getip()
            ];
            $uploaddata = array_merge($upload, $data);
            $r = db('upload')->insert($uploaddata);

            return ['msg' => $r ? '上传成功' : '上传头像失败', 'status' => $r ? 200 : 000];
        }
    }
    
    /**
     * 发送验证码
     *
     * @param array $params 包含验证码类型（邮箱或手机）的参数数组
     * @return array 返回操作结果
     * @author zhaosong
     */
    public static function send_code($params): array
    {
        if ($params['type'] == 'email') {
            if (empty($params['email'])) {
                return json(['msg' => '邮箱不能为空！', 'status' => 0]);
            }
            Other::send_email_code($params['email']);
        }
        if ($params['type'] == 'phone') {
            if (empty($params['phone'])) {
                return json(['msg' => '手机不能为空！', 'status' => 0]);
            }
            
            Other::send_phone_code($params['phone']);
        }
    }

    /**
     * 账户逻辑
     *
     * @param array $params
     * @return array
     */
    public static function accountlogic($params): array
    {
        $db = db('member');
        $userid = get_cookie('_userid');

        if (empty($params['type'])) {
            return ['msg' => '数据类型错误！', 'status' => 0];
        }

        $type = $params['type'];
        $updateData = [];

        if ($type == 'password') {
            if (empty($params['password'])) {
                return ['msg' => '密码不能为空！', 'status' => 0];
            }
            if ($params['password'] != $params['password2']) {
                return ['msg' => '密码不一致！', 'status' => 0];
            }
            $updateData['password'] = md5s($params['password']);
            
        } elseif ($type == 'email') {
            $is_email = Other::isMail($params['code'],$params['email']);
            $updateData['email'] = $is_email ? $params['email']:'';
            $updateData['email_status'] = $is_email ? 1:0;
            del_cookie('email_code');
        } elseif ($type == 'phone') {
            if (empty($params['code'])) {
                return ['msg' => '验证码不能为空！', 'status' => 0];
            }
            $is_phone = Other::isPhone($params['code'],$params['phone']);
            $updateData['phone'] = $is_phone ? $params['phone']:'';
            del_cookie('phone_code');
        } else {
            return ['msg' => '数据类型错误！', 'status' => 0];
        }

        $db->where(['userid' => $userid])->update($updateData);
        return ['msg' => ucfirst($type) . '更新成功！', 'status' => 200];
    }

    /**
     * 点转逻辑
     *
     * @param array $params
     * @return array
     */
    public static function pointlogic($params): array
    {
        $userid = get_cookie('_userid');
        $db = db('member');
        $username = get_userinfo($userid, 'username');

        if (empty($params['value'])) {
            return ['msg' => '请选择余额！', 'status' => 0];
        }

        if ($params['paytype'] == 'balance'){
            $p = buy::spend($params['value'], $userid, $username, '余额', '购买积分', '自助购买');
            if ($p) {
                $r = buy::add($params['point'], $params['userid'], get_userinfo($params['userid'], 'username'), '积分', '购买积分', '自助购买');
                return ['msg' => '支付成功！', 'status' => 200];
            }
        }
        
        if ($params['paytype'] == 'alipay'){
            Alipay::pay('积分购买',$params['value'],"购买积分共{$params['value']}元",'001','积分',$params['point']);
        }
        
        return ['msg' => '目前只支持余额或者支付宝支付！', 'status' => 0];
        
    }
    
    
    /**
     * 支付逻辑
     *
     * @param array $params
     * @return array
     */
    public static function amountlogic($params): array
    {
        if (empty($params['value'])) {
            return ['msg' => '请选择余额！', 'status' => 0];
        }

        if ($params['paytype'] != 'alipay') {
            return ['msg' => '目前只支持支付宝支付！', 'status' => 0];
        }

        Alipay::pay('余额购买',$params['value'],"购买余额共{$params['value']}元",'001','余额');
    }

    /**
     * 用户逻辑
     *
     * @param string $s
     * @return array
     */
    public static function userlogic($s): array
    {
        $where = [];
        $where['nickname'] = ['like', '%' . $s . '%'];
        $data = db('member')->field('userid,nickname,userpic,email,groupid')->where($where)->select();
        $data = array_map(function ($item) {
            $item['userpic'] = get_avatar($item['userid']);
            $item['group'] = get_userlv($item['userid']);
            $item['url'] = '/user/' . $item['userid'];
            $item['email'] = maskEmail($item['email']);
            return $item;
        }, $data);
        return ['data' => $data, 'status' => 200];
    }

    /**
     * 转账逻辑
     *
     * @param array $params
     * @return array
     */
    public static function transferlogic($params): array
    {
        $userid = get_cookie('_userid');
        $db = db('member');
        $username = get_userinfo($userid,'username');
        
        if(empty($params['number'])){
            return ['msg' => '请选择数量！', 'status' => 0];
        }
        if($userid == $params['userid']){
            return ['msg' => '自己不能给自己转账！', 'status' => 00];
        }
        // 计算手术费
        $percentage = (intval($params['number']) * 10) / 100;
        
        $p = buy::spend($params['number'],$userid,$username,'积分','积分转出','自助转账');
        if($p){
            $r = buy::add($percentage,$params['userid'],get_userinfo($params['userid'],'username'),'积分','积分转入','自助转账');
            return ['msg' => '转账成功！', 'status' => 200];
        }

    }

    /**
     * 关注逻辑
     *
     * @param int $followid
     * @return array
     */
    public static function followlogic($followid): array
    {
        $userid = get_cookie('_userid');
        if (empty($userid)) {
            return ['msg' => '请先登录！', 'status' => 00];
        }
        if($userid == $followid){
            return ['msg' => '自己不能关注自己！', 'status' => 00];
        }
        
        $array = is_follow(intval($userid), intval($followid));
        $db = db('follow');
        if ($array) {
            $db->where(['userid' => $userid, 'followid' => $followid])->delete();
            return ['msg' => '取消关注成功！', 'status' => 200, 'follow' => 0];
        } else {
            $db->insert(['userid' => $userid, 'followid' => $followid, 'inputtime' => time()]);
            return ['msg' => '关注成功！', 'status' => 200, 'follow' => 1];
        }

    }
    
    
    /**
     * 绑定逻辑
     *
     * @param array $params 参数数组，包含解绑类型
     * @return void
     * @author zhaosong
     */
    public static function bindlogic(array $params = [])
    {
        if (empty($params['type'])) {
            showmsg('请提供绑定类型');
        }
        
        $is = get_cookie('_bind');
        if(!$is){
            cookie('_bind',$params['type']);
            return header("Location: " . url('/member/index/qq_login'));
        }
        else{
            $userid = get_cookie('_userid');
            if ($params['type'] == 'qq') {
                if(empty($params['openid'])) {
                    del_cookie('_bind');
                    showmsg('绑定中断，请重新再试',url('/member/index/account'));
                }
                db('member')->where(['userid' => $userid])->update(['openid' => $params['openid']]);
                del_cookie('_bind');
                showmsg('QQ绑定完成！',url('/member/index/account'));
            }
        }
        
    }      
    
    /**
     * 解绑逻辑
     *
     * @param array $params 参数数组，包含解绑类型
     * @return void
     * @author zhaosong
     */
    public static function unbindlogic(array $params = [])
    {
        if (empty($params['type'])) {
            showmsg('请提供解绑类型',url('/member/index/account'));
        }
    
        $userid = get_cookie('_userid');
        if ($params['type'] == 'qq') {
            db('member')->where(['userid' => $userid])->update(['openid' => '']);
            showmsg('QQ解绑完成！',url('/member/index/account'));
        }
    }
    
    /**
     * 订单列表
     * @author zhaosong
     * @param array $params 参数数组
     * @return array 返回订单列表数据和状态
     */
    public static function order_list(array $params = [])
    {
        // 如果参数中包含订单ID，则删除该订单
        if (!empty($params['id'])) {
            db('order')->where(['id' => $params['id']])->delete();
            return ['msg' => '订单删除成功！', 'status' => 200];
        }
    
        // 初始化查询条件
        $where = [];
        $where['userid'] = get_cookie('_userid');
    
        // 根据参数中的状态设置查询条件
        $params['status'] == 2 ? null : $where['status'] = empty($params['status']) ? 0 : 1;
    
        // 查询订单数据并按ID降序排列，限制结果数量为4
        $data = db('order')->where($where)->order('id DESC')->limit(4)->select();
    
        // 使用array_map处理数据，添加图片和格式化时间
        $data = array_map(function ($item) {
            $item['images'] = $item['type'] == '积分' ? '/static/them/images/积分.jpg' : '/static/them/images/余额.svg';
            $item['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
            return $item;
        }, $data);
    
        // 返回处理后的订单数据和状态
        return ['data' => $data, 'status' => 200];
    }

}