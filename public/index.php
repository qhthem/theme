<?php
// +----------------------------------------------------------------------
// | QHPHP [ 代码创造未来，思维改变世界。 ] 程序入口文件
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 https://www.astrocms.cn/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------

//调试模式：开发阶段设为开启true，部署阶段设为关闭false。
define('APP_DEBUG', false);
//根路径
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// 1. 加载基础文件
require ROOT_PATH . 'vendor/autoload.php';
// 2.静态化引入APP类
use qhphp\app\App;
// 3. 执行应用
App::run();

