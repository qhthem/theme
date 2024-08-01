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
use app\member\model\AccountSecurityCalculator as Calculator;
/**
 * Index 类
 *
 * @author zhaosong
 */
class Index extends Common
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页逻辑
     */
    public function indexlogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'index');
    }

    /**
     * 发布逻辑
     */
    public function postlogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);

        $model = db('model')->where(['status' => 1])->field('modelid,name')->select();

        include view('member', 'post');
    }

    /**
     * 账户逻辑
     */
    public function accountlogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);

        $calculator = new Calculator();
        $userinfo = $this->memberinfo;
        $passwordStrength = $userinfo['password'];
        $twoFactorAuth = true;
        $loginHistory = [
            'successCount' => $userinfo['loginnum'],
            'failedCount' => 0
        ]; // 登录历史
        $accountActivity = [
            'lastActivity' => time(),
            'activityCount' => $userinfo['experience']
        ]; // 账户活动
        $personalInfoSecurity = [
            'emailVerified' => $userinfo['email_status'] ? true : false,
            'phoneVerified' => $userinfo['phone'] ? true : false,
        ]; // 个人信息安全性
        $security = $calculator
            ->calculateSecurityIndex($passwordStrength, $twoFactorAuth, $loginHistory, $accountActivity, $personalInfoSecurity);
        $riskLevel = $calculator->getRiskLevel($security);
        
        include view('member', 'account');
    }

    /**
     * 成长逻辑
     */
    public function growthlogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);

        $growth = db('group')->field('groupid,experience')->select();
        $growth = array_map(function ($item) {
            $item['icon'] = '/static/icon/lv' . $item['groupid'] . '.png';
            return $item;
        }, $growth);

        $user_exp = db('group')->where(['groupid' => $memberinfo['groupid']])->value('experience');
        $upgrade = (intval($user_exp) - intval($memberinfo['experience']));
        $percentage = number_format(($memberinfo['experience'] / $user_exp) * 100, 2);

        include view('member', 'growth');
    }

    /**
     * 设置逻辑
     */
    public function settingslogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'settings');
    }

    /**
     * 订单逻辑
     */
    public function orderlogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'order');
    }

    /**
     * VIP 逻辑
     */
    public function viplogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'vip');
    }

    /**
     * 写作逻辑
     */
    public function writelogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'write');
    }
    
    
    /**
     * 绑定邮箱
     */
    public function bind_maillogic()
    {
        $memberinfo = $this->memberinfo;
        extract($memberinfo);
        include view('member', 'bind_mail');
    }    
    
}