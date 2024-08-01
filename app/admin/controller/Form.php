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
namespace app\admin\controller;
use app\admin\logic\Form as Formlogic;
/**
 * 表单管理类
 * @author zhaosong
 */
class Form extends Base
{
    /**
     * 创建表单
     * @return json
     */
    public function createform()
    {
        if (isPost()) {
            json(Formlogic::createform(Request()::post('modelid')));
        }
    }
}
