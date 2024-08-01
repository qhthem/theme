<?php
// +----------------------------------------------------------------------
// | 网站配置设置
// +----------------------------------------------------------------------

return [
    // 站点名称
    'site_name' => 'Astro主题',
     // 站点关键字
    'site_keyword' => 'Astro主题模板_Astro企业主题_Astro博客主题_Astro功能主题_Astro系统',
     // 站点描述
    'site_description' => 'Astro主题是一款个人博客主题、企业主题、博客主题、功能主题、系统。',
     // 版权信息
    'site_copyright' => 'Powered By Astro_内容管理系统 © 2024',
     // 站点备案号
    'site_filing' => '黔ICP备2022000828号-2',
    // 公安备案号
    'site_security_number' => '贵公网安备52052102000092号',
     // 统计代码
    'site_code' => '',
     // 站点logo
    'site_logo' => '/uploads/20240506/2cc698ba618d4a11667cb579cfbc218a.png',
     // 禁止登录后台的IP
    'admin_prohibit_ip' => '',
     // 屏蔽词
    'prohibit_words' => '',
     // 前端IP黑名单
    'blacklist_ip' => '',
     // 是否开启操作日志
    'isoperation_Log' => 1,
    // 百度推送token
    'baidu_push_token' => 'hcQnexwyBHUHKRUJ',
    // 每天登录获取多少积分
    'login_point' => 5,
    // 会员注册默认积分
    'member_point' => 5,
    // 是否开启会员注册
    'member_register' => 0,
    // 是否开启会员注册验证
    'member_check' => 0,
    //  评论获取积分量
    'comment_point' => 2,
    //  QQ互联账号
    'qq_appid' => '102112008',
    // QQ互联秘钥
    'qq_secret' => 'c8yb7UeaIn6JI2nO',
    // QQ互联回调地址
    'qq_url' => get_Domain(). 'member/index/qq_login.html',
    // 邮箱host地址
    'Host_qq' => 'smtp.qq.com',
    // 邮箱发送用户名
    'Username_qq' => '1716892803@qq.com',
    // 邮箱授权码
    'Password_qq' => 'jdnxyiietqfdfcdf',
    // 邮箱验证码模版
    'email_them' => '您好，您本次的验证码：{code}，该验证码5分钟之内有效，为了保障您的账户安全，请勿向他人泄漏验证码信息。此致',
    // 短信包账户
    'smsbao_account' => '1716892803',
    // 短信包秘钥
    'smsbao_password' => 'q12345678',
];