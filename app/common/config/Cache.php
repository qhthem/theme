<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 默认缓存驱动
    'cache_default' => 'File',
    // 缓存连接方式配置
    'stores'  => [
        'File' => [
            // 缓存保存目录
            'path'       => app()::getBasePath().'common'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR,
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
        ],
        
        // redis缓存
        'Redis'   =>  [
            // 端口
            'port'   => 6379,
            // 服务器地址
            'host'       => '127.0.0.1',
            //  Redis 密码
            'password' => '',
            //  Redis 数据库
            'database'=> 1
        ], 
    ],
];
