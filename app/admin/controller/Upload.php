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
use app\admin\logic\Upload as Uploadlogic;
class Upload extends Base
{
    /**
     * 列出上传文件
     */
    public function list()
    {
        if (isPost()) {
            json(Uploadlogic::listlogic(Request()::post()));
        }
    }
    
    /**
     * 上传文件
     */
    public function upload()
    {
        if (isPost()) {
            $post = Request()::post('app');
            $file = Request()::file('file');
            json(Uploadlogic::uploadlogic($file,$post));
        }
    }
    
    /**
     * 上传文件（WangEditor）
     */
    public function upload_wang()
    {
        if (isPost()) {
            json(Uploadlogic::upload_wanglogic());
        }
    }
    
    /**
     * 删除文件
     */
    public function delete()
    {
        if (isPost()) {
            json(Uploadlogic::deletelogic(Request()::post('id')));
        }
    }
    
    /**
     * 文本上传
     */
    public function textup()
    {
        if (isPost()) {
            json(Uploadlogic::textuplogic(Request()::post('id')));
        }
    }
}