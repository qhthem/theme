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
namespace app\index\logic;

class Search
{
    /**
     * 搜索页逻辑处理
     *
     * @author zhaosong
     */
    public static function indexlogic()
    {
        $title = site('site_name');
        $keywords = site('site_keywords');
        $description = site('site_description');

        $s = Request()::get('keyword', '');

        if (!$s || !is_string($s)) showmsg('缺少参数！',false);
        $q = str_replace('%', '', html_special_chars(strip_tags(trim($s))));
        if (strlen($q) < 2 || strlen($q) > 30) {
            showmsg('你输入的字符长度需要是 2 到 30 个字符！',false);
        }

        $modelid = Request()::get('modelid', 0);
        $catid = Request()::get('catid', 0);

        include view('index', 'search');
    }
}