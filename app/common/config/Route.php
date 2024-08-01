<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

return [
     // 默认应用名
    'default_app'           => 'index',
     // 默认控制器名
    'default_controller'    => 'index',
     // 默认操作名
    'default_action'        => 'index',
    //是否开启路由映射
    'route_mapping'         => true,
    //路由映射规则
    'route_rules'           => [],
    //路由绑定规则
    'route_build'           => ['captcha' =>'qhphp\captcha\controller\captcha\captcha_img'],
    // 应用映射
    'app_map'               => [],
    // 自定义配置路由文件路径
    'custom_route_path'    => ROOT_PATH.'extend'.DIRECTORY_SEPARATOR.'route'.DIRECTORY_SEPARATOR.'Route.php'
];