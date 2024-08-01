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

class Finance
{
    /**
     * 生成订单号
     *
     * @return string
     */
    private static function generateOrderNumber()
    {
        $date = date('Ymd');
        $randomNumber = rand(100000, 999999);
        return $date . $randomNumber;
    }

    /**
     * 验证会员信息
     *
     * @param object $member
     * @param int $userid
     * @param string $username
     * @param float $money
     * @param string $paytype
     * @return mixed
     */
    private static function verifyMember($member, $userid, $username, $money, $paytype)
    {
        if (!$userid || !$username || $money < 0) {
            return json(['status' => 0, 'msg' => '参数不正确']);
        }

        $res = $member->field('point,amount')->where(['userid' => $userid])->find();
        if ($paytype === '积分') {
            if (($res['point'] - $money) < 0) {
                return json(['status' => 0, 'msg' => '积分不足本次交易']);
            }
        } else {
            if (($res['amount'] - $money) < 0) {
                return json(['status' => 0, 'msg' => '账户余额不足本次交易']);
            }
        }

        return true;
    }

    /**
     * 添加财务记录
     *
     * @param float $value
     * @param int $userid
     * @param string $username
     * @param string $paytype
     * @param string $msg
     * @param string $remarks
     * @return mixed
     */
    public static function add($value, $userid, $username, $paytype, $msg, $remarks)
    {
        $data = [];
        $data['trade_sn'] = self::generateOrderNumber();
        $data['money'] = $value;
        $data['userid'] = $userid;
        $data['username'] = $username;
        $data['type'] = 0;
        $data['paytype'] = $paytype;
        $data['msg'] = htmlspecialchars($msg);
        $data['remarks'] = htmlspecialchars($remarks);
        $data['creat_time'] = time();
        $data['ip'] = getip();
        $data['status'] = 1;

        $field = $paytype == '积分' ? 'point' : 'amount';
        db('member')->where(['userid' => $userid])->setInc($field, $value);

        return db('pay')->insert($data);
    }

    /**
     * 扣除财务记录
     *
     * @param float $value
     * @param int $userid
     * @param string $username
     * @param string $paytype
     * @param string $msg
     * @param string $remarks
     * @return mixed
     */
    public static function spend($value, $userid, $username, $paytype, $msg, $remarks)
    {
        $member = db('member');

        $data = [];
        $data['trade_sn'] = self::generateOrderNumber();
        $data['money'] = $value;
        $data['userid'] = $userid;
        $data['username'] = $username;
        $data['type'] = 1;
        $data['paytype'] = $paytype;
        $data['msg'] = htmlspecialchars($msg);
        $data['remarks'] = htmlspecialchars($remarks);
        $data['creat_time'] = time();
        $data['ip'] = getip();

        self::verifyMember($member, $userid, $username, $value, $paytype);

        $field = $paytype == '积分' ? 'point' : 'amount';
        $member->where(['userid' => $userid])->setDec($field, $value);
        return db('pay')->insert($data);
    }
}