<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 设置默认的时区
    'default_timezone' => 'Asia/Shanghai',
    // 禁止URL访问的应用列表
    'deny_app_list'    => ['common'],
    // 扩展函数文件
    'extra_file_list'  => [app()::getExtendPath().'function'.DIRECTORY_SEPARATOR.'Function.php'],
    // 扩展监听行为文件
    'extra_hook_behavior'  => app()::getExtendPath().'behavior'.DIRECTORY_SEPARATOR.'Behavior.php',
    //  token验证秘钥
    'token_request'    => '178f360b64e1ee81cc350f961e780d06',
    // 开启腾讯云IM 加密回调
    'app_TLSSigAPIv2' => true,
    // 腾讯云IMappid
    'app_sdkappid' => 1600038790,
    // 腾讯云IMappid
    'app_sdksecretkey' => 'dba6dda8e8f96449be39e971b8a3d198ea9880808986a9c402f4efb4976425c4',
    // 开启自定义邮箱验证摸版
    'is_email_temple' => true,
    // 异常页面的模板文件
    'exception_tmpl'   => app()::getQhphpPath() . 'tpl/debug.tpl',
    // 默认语言
    'default_lang'    => 'zh-cn',
    // 默认跳转页面对应的模板文件
    'dispatch_tmpl' => app()::getQhphpPath() . 'tpl/dispatch_jump.tpl',
    // 空控制器:空控制器的概念是指当系统找不到指定的控制器名称的时候，系统会尝试定位当前应用下的空控制器
    'empty_controller' => 'app\\index\\controller\\Test'
];
