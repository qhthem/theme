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
use extend\Alipay\f2fpay\model\builder\AlipayTradePrecreateContentBuilder as Alipays;
use extend\Alipay\f2fpay\service\AlipayTradeService;
use extend\Alipay\aop\AopClient;
use qhphp\logs\Log;
use app\member\model\Finance as buy;

class Alipay
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
     * 插入支付记录
     *
     * @param string $order_sn 订单号
     * @param string $paytype 支付类型
     * @param float $totalAmount 支付金额
     * @param string $subject 支付主题
     * @param string $body 支付描述
     * @param int $type 支付类型
     * @author zhaosong
     */
    private static function insert_pay($order_sn, $paytype, $totalAmount, $subject, $body, $type,$point = 0)
    {
        $userid = get_cookie('_userid');
        $data = [
            'order_sn' => $order_sn,
            'userid' => $userid,
            'addtime' => time(),
            'paytype' => $paytype,
            'type' => $type,
            'money' => $totalAmount,
            'point' => $point,
            'subject' => $subject,
            'ip' => getip(),
            'msg' => $body,
            'status' => 0,
        ];
    
        db('order')->insert($data);
    }
     
    /**
     * 支付方法
     *
     * @param string $subject 支付主题
     * @param float $totalAmount 支付金额
     * @param string $body 支付描述
     * @param string $operatorId 操作员ID
     * @param int $type 支付类型
     * @return Json
     * @author zhaosong
     */
    public static function pay($subject, $totalAmount, $body, $operatorId, $type,$point = 0)
    {
        $outTradeNo = self::generateOrderNumber();
        $timeExpress = "5m";
    
        $qrPayRequestBuilder = new Alipays();
        $qrPayRequestBuilder->setOutTradeNo($outTradeNo);
        $qrPayRequestBuilder->setTotalAmount($totalAmount);
        $qrPayRequestBuilder->setTimeExpress($timeExpress);
        $qrPayRequestBuilder->setSubject($subject);
        $qrPayRequestBuilder->setBody($body);
    
        $qrPayRequestBuilder->setOperatorId($operatorId);
    
        // 调用qrPay方法获取当面付应答
        $configPath = app()::getConfigPath() . 'Alipay.php';
        if (file_exists($configPath)) {
            $config = include $configPath;
        } else {
            return json(['status' => 0, 'msg' => '配置文件不存在']);
        }
    
        $qrPay = new AlipayTradeService($config);
        $qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
    
        // 根据状态值进行业务处理
        switch ($qrPayResult->getTradeStatus()) {
            case "SUCCESS":
                $response = $qrPayResult->getResponse();
                $qrcode = '/static/qrcode/api.php?data=' . $response->qr_code . '&powered=Astro';
                self::insert_pay($outTradeNo, '支付宝', $totalAmount, $subject, $body, $type,$point);
                return json(['status' => 200, 'msg' => '支付宝创建订单二维码成功', 'qrcode' => $qrcode, 'order_sn' => $outTradeNo]);
                break;
            case "FAILED":
                return json(['status' => 100, 'msg' => '支付宝创建订单二维码失败!!']);
                break;
            case "UNKNOWN":
                return json(['status' => 100, 'msg' => '系统异常，状态未知!!!']);
                break;
            default:
                return json(['status' => 100, 'msg' => '不支持的返回状态，创建订单二维码返回异常!']);
                break;
        }
        return;
    }
    
    /**
     * 公共支付宝异步通知处理方法
     *
     * @param array $_POST 通知参数
     * @author zhaosong
     */
    public static function public_notify_alipay()
    {
        $out_trade_no = $_POST['out_trade_no'];
        $order = db('order')->where(['order_sn' => $out_trade_no])->find();
        if (!$order) exit('fail');
    
        $res = self::_check_notify($_POST, $order);
        $result = $_POST['trade_status'];;
    
        if ($result && $res) {
            if ($result == 'TRADE_SUCCESS' && $order['status'] == 0) {
                self::update_order($order, $out_trade_no);
            }
            echo "success";
        } else {
            echo "fail";
        }
        exit;
    }
    
    /**
     * 更新订单状态
     *
     * @param array $order 订单信息
     * @param string $transaction 交易号
     * @return bool
     * @author zhaosong
     */
    private static function update_order(array $order, string $transaction)
    {
        $result = db('order')->where(['id' => $order['id'], 'order_sn' => $transaction])
        ->update(['status' => 1, 'paytime' => time()]);
        if ($result) {
            $num = !empty($order['point']) ? $order['point']:$order['money'];
            buy::add($num, $order['userid'], get_userinfo($order['userid'], 'username'), $order['type'], '购买' . $order['type'], '自助购买');
        } else {
            return false;
        }
    }
    
    /**
     * 获取订单状态
     *
     * @param string $out_trade_no 订单号
     * @return Json
     * @author zhaosong
     */
    public static function order_status(string $out_trade_no)
    {
        $userid = get_cookie('_userid');
        if (!$out_trade_no) return json(['status' => 0, 'msg' => '缺少参数']);
        $data = db('order')->where(['order_sn' => $out_trade_no, 'status' => 1])->find();
        if (!$data || $data['userid'] != $userid) return json(['status' => 0, 'msg' => '订单信息错误']);
        return json(['status' => 200, 'msg' => '支付成功,正在跳转']);
    }
    
    /**
     * 检查支付宝异步通知参数
     *
     * @param array $params 通知参数
     * @param array $order 订单信息
     * @return bool
     * @author zhaosong
     */
    private static function _check_notify(array $params, array $order)
    {
        $configPath = app()::getConfigPath() . 'Alipay.php';
        if (file_exists($configPath)) {
            $config = include $configPath;
        } else {
            Log::record('配置文件不存在', 'info');
        }
        $notifyArr = array(
            'out_trade_no' => $params['out_trade_no'],
            'total_amount' => $params['total_amount'],
            'app_id' => $params['app_id'],
        );
        $paramsArr = array(
            'out_trade_no' => $params['out_trade_no'],
            'total_amount' => $order['money'],
            'app_id' => $config['app_id'],
        );
        $result = array_diff_assoc($paramsArr, $notifyArr);
        if ($result) {
            Log::record('支付宝异步回调，参数对比失败：' . json_encode($params));
            return false;
        }
    
        $aop = new AopClient;
        $aop->alipayrsaPublicKey = $config['alipay_public_key'];
        return $aop->rsaCheckV1($params, '', 'RSA2');
    }  
     
}