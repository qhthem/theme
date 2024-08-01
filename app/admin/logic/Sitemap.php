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
namespace app\admin\logic;
use app\admin\model\Sitemap as map;

class Sitemap
{
    /**
     * 获取地图索引信息
     *
     * @return array
     */
    public static function indexlogic()
    {
        if (is_file('sitemap.xml')) {
            $make = date('Y-m-d H:i:s', filemtime('sitemap.xml'));
        } else {
            $make = 0;
        }
        return ['status' => 200, 'data' => Session()::get('map') ?: ['priority' => '1', 'changefreq' => 'always'], 'map' => $make];
    }

    /**
     * 生成地图
     *
     * @param array $params 参数
     * @return array
     */
    public static function mianlogic(array $params)
    {
        $priority = $params['priority'];
        $changefreq = $params['changefreq'];

        $map = new map($_SERVER['HTTP_HOST']);
        // 生成文章页
        $article = db('article')->field('url')->select();
        foreach ($article as $key => $vo) {
            $map->addUrl($vo["url"], date('Y-m-d'), $priority, $changefreq);
        }
        $map->saveSitemap('sitemap.xml');
        Session()::set('map', ['priority' => $priority, 'changefreq' => $changefreq]);
        return ['status' => 200, 'msg' => '生成地图成功!'];
    }

    /**
     * 删除地图
     *
     * @return array
     */
    public static function deletelogic()
    {
        if (is_file('sitemap.xml')) {
            if (!@unlink('sitemap.xml')) {
                return ['status' => 0, 'msg' => '文件不存在!'];
            }
        }
        return ['status' => 200, 'msg' => '删除生成地图成功!'];
    }
}