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
use extend\phpmailer\PHPMailer;
use extend\phpmailer\Exception;

class Other
{
    /**
     * 验证邮箱验证码
     *
     * @param string $code 验证码
     * @param string $email 邮箱地址
     * @return array 返回操作结果
     * @author zhaosong
     */
    public static function isMail(string $is_code, string $email)
    {
        if (empty($is_code)) {
            return json(['msg' => '验证码不能为空！', 'status' => 0]);
        }
        if (empty($email)) {
            return json(['msg' => '邮箱不能为空！', 'status' => 0]);
        }

        $code = get_cookie('email_code');

        if (empty($code) || $code != $is_code) {
            return json(['msg' => '验证码错误！', 'status' => 0]);
        }

        return true;
    }

    /**
     * 发送邮箱验证码
     *
     * @param string $email 邮箱地址
     * @return array 返回操作结果
     * @author zhaosong
     */
    public static function send_email_code(string $email,bool $cookie = true)
    {
        $code = mt_rand(1000, 9999);
        $subject = site('site_name') . '的邮箱验证码';
        $email_content = C('email_them');
        $body = C('is_email_temple') ? self::send_temple_code($code):str_replace('{code}', $code, $email_content);
        
        if($cookie) cookie('email_code', $code);
        
        return self::sendMail($email, $subject, $body);
    }

    /**
     * 发送邮件
     *
     * @param string $to 收件人邮箱地址
     * @param string $subject 邮件主题
     * @param string $body 邮件内容
     * @return void
     * @author zhaosong
     */
    public static function sendMail(string $to, string $subject, string $body ,bool $is_msg = false)
    {
        $mail = new PHPMailer(true);

        try {
            // 邮件服务器设置
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = C('Host_qq');
            $mail->SMTPAuth = true;
            $mail->Username = C('Username_qq');
            $mail->Password = C('Password_qq');
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // 发件人
            $mail->setFrom(C('Username_qq'), site('site_name'));

            // 收件人
            $mail->addAddress($to);

            // 邮件内容
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // 发送邮件
            $mail->send();
            $is_msg ? true : json(['status' => 200, 'msg' => '邮件发送成功']);
        } catch (Exception $e) {
            json(['status' => 100, 'msg' => "邮件发送失败: {$mail->ErrorInfo}"]);
        }
    }
    
    /**
     * 验证手机验证码是否正确
     * 
     * @author zhaosong
     * @param string $code 验证码
     * @param string $phone 手机号码
     * @return bool|string 返回json格式的验证结果或者true
     */
    public static function isPhone(string $code, string $phone)
    {
        if (empty($code)) {
            return json(['msg' => '验证码不能为空！', 'status' => 0]);
        }
        if (empty($phone)) {
            return json(['msg' => '手机不能为空！', 'status' => 0]);
        }
    
        $Captcha_ = get_cookie('phone_code');
    
        if (empty($Captcha_) || $Captcha_ != $code) {
            return json(['msg' => '验证码错误！', 'status' => 0]);
        }
    
        return true;
    }
    
    /**
     * 发送手机验证码
     * 
     * @author zhaosong
     * @param string $phone 手机号码
     * @return array 返回发送状态信息
     */
    public static function send_phone_code(string $phone)
    {
        $statusStr = [
            0 => "短信发送成功",
            -1 => "参数不全",
            -2 => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            30 => "密码错误",
            40 => "账号不存在",
            41 => "余额不足",
            42 => "帐户已过期",
            43 => "IP地址限制",
            50 => "内容含有敏感词"
        ];
    
        $randomNumber = mt_rand(1000, 9999);
        $siteName = site('site_name');
        $smsapi = "http://api.smsbao.com/";
        $user = C('smsbao_account');
        $pass = md5(C('smsbao_password'));
        $content = "您本次的验证码为" . $randomNumber . '【' . $siteName . '】';
    
        $sendurl = $smsapi . "sms?u=" . $user . "&p=" . $pass . "&m=" . $phone . "&c=" . urlencode($content);
        $result = file_get_contents($sendurl);
        if (empty($result)) {
            cookie('phone_code', $randomNumber);
            return json(['status' => 200, 'msg' => '发送成功']);
        } else {
            return json(['status' => 100, 'msg' => "发送失败: {$statusStr[$result]}"]);
        }
    }
    
    /**
     * 发送模板验证码邮件的静态方法。
     * 
     * @param string $code 验证码字符串。
     * @return string 返回包含HTML内容的字符串，用于显示在邮件正文中。
     * 
     * @author zhaosong
     */
    public static function send_temple_code(string $code)
    {
       $html = '<div style="padding: 15px;background: #f8f8f8;max-width: 375px;"> 
                   <div style="background: #fff;border-radius: 8px;padding: 15px;"> 
                    <div style="width: 100%;display: flex;align-items: center;justify-content: center;"> 
                     <text style="font-size: 16px;font-weight: bold;">'.C('site_name').'</text> 
                    </div> 
                    <div style="margin: 15px 0;font-size: 14px;">
                      您正在验证邮箱账号，验证码 20 分钟有效，请及时使用。如非本人操作，请忽略该邮件 
                    </div> 
                    <div style="width: 100%;height: 60px;background: #f5f5f5;line-height: 60px;text-align: center;border-radius: 4px;"> 
                     <div style="font-size: 20px;font-weight: bold;">'.$code.'</div> 
                    </div> 
                   </div> 
                  </div>';
        
        return $html;
    }
    

}