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

class Index
{
    /**
     * 首页逻辑处理
     *
     * @author zhaosong
     */
    public static function indexlogic()
    {
        $title = site('site_name');
        $keywords = site('site_keyword');
        $description = site('site_description');

        include view('index', 'index');
    }

    /**
     * 列表页逻辑处理
     *
     * @author zhaosong
     */
    public static function listslogic()
    {
        $catid = Request()::get('catid', 0);
        if (!$catid) showmsg('缺少参数',false);

        $catinfo = get_category($catid);
        if (empty($catinfo)) showmsg('栏目参数错误', false);
        extract($catinfo);

        $template = $catinfo['type'] == 2 ? $catinfo['category_template'] : $catinfo['list_template'];

        $title = !empty($catinfo['title']) ? $catinfo['title'] : site('site_name');
        $keywords = !empty($catinfo['keywords']) ? $catinfo['keywords'] : site('site_keyword');
        $description = !empty($catinfo['description']) ? $catinfo['description'] : site('site_description');

        include view('index', $template);
    }

    /**
     * 内容页逻辑处理
     *
     * @author zhaosong
     */
    public static function showlogic()
    {
        $catid = Request()::get('catid', 0);
        $id = Request()::get('id', 0);
        if (!$catid || !$id) showmsg('内容参数错误',false);

        $catinfo = get_category($catid);
        if (!$catinfo) showmsg('栏目参数错误',false);

        $modelid = $catinfo['modelid'];
        $template = $catinfo['show_template'];

        $tablename = get_model_where(['modelid' => $modelid],'tablename');
        if (!$tablename) showmsg('内容模型错误',false);

        $db = db($tablename);
        $data = cache_get_or_set($tablename.$id.'_content', function() use ($db,$id){
            return $db->where(['id' => $id])->find();
        }, 3600);
        // $data = $db->where(['id' => $id])->find();
        if (!$data) showmsg('内容不存在或被删除！', false);
        extract($data);

        $db->where(['id' => $id])->setInc('click');

        include view('index', $template);
    }
}