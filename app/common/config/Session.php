<?php
// +----------------------------------------------------------------------
// | 会话设置
// +----------------------------------------------------------------------

return [
   
    'session_expire' => 43200,
    // 会话保存路径
    'session_path' => app()::getBasePath().'common'.DIRECTORY_SEPARATOR.'session',
    // 会话域名
    'session_domain' => '/',
    // 启用安全会话（HTTPS）
    'session_secure' => true,
    // 仅允许HTTP访问会话cookie
    'session_httponly' => true,
    
    'session_id'             => '',
    // SESSION_ID的提交变量,解决flash上传跨域
    'session_var_session_id' => '',
    // SESSION 前缀
    'session_prefix'         => 'qhphp_',
    // 驱动方式 支持redis memcache memcached
    'session_type'           => '',
    // 是否自动开启 SESSION
    'session_auto_start'     => true,
];