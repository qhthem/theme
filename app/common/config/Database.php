<?php
// +----------------------------------------------------------------------
// | 数据库设置
// +----------------------------------------------------------------------

return [
    // 数据库链接
    'db_type' => 'pdo',       
    // 服务器地址
    'db_host' => env('dbhost','127.0.0.1'),
    // 数据库名
    'db_name' => env('dbname'),
    // 用户名
    'db_user' => env('dbuser'),
    // 密码
    'db_pwd' => env('dbpw'),
    // 端口
    'db_port' => env('dbport',3306) ,
    // 数据库表前缀
    'db_prefix' => env('dbprefix','qh_'),
    // 写入初始数据
    'db_write'  => 'qh',
    // 数据库严格模式
    'db_errmode' => false,
    // 备份路径
    'db_backups' => app()::getBasePath().'common'.DIRECTORY_SEPARATOR.'backups'.DIRECTORY_SEPARATOR,
    //  数据库设置sql_mode 主要避免提示数值库字段doesn t have a default value 的报错
    'db_sql_mode' => 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'
];