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

/**
 * Theme 类
 * @author zhaosong
 */
class Theme
{
    /**
     * 索引逻辑方法
     * @param array $params 参数数组（默认为空数组）
     * @return array 返回包含状态码和数据的 JSON 格式响应
     */
    public static function indexlogic(array $params = [])
    {
        $data = self::no_app_list();
        return json(['status' => 200, 'data' => $data]);
    }

    /**
     * 获取无应用列表的方法（受保护）
     * @return array 返回处理后的应用列表数据
     */
    protected static function no_app_list()
    {
        $dirs = $dirs_arr = $apps = $item = [];
        $appPath = app()::getBasePath();
        $dirs = glob($appPath.'*', GLOB_ONLYDIR);

        foreach ($dirs as $d) {
            $dirs_arr[] = basename($d);
        }

        foreach ($dirs_arr as $d) {
            $file = $appPath. $d.'/service/App.php';
            if (is_file($file)) {
                $ck = include_once $file;
                $item[] = array_merge($ck, ['app' => $d]);
            }
        }

        return $item;
    }

    /**
     * 更新逻辑方法
     * @param array $params 参数数组（默认为空数组）
     * @return array 返回包含状态码和消息的 JSON 格式响应
     */
    public static function updatelogic(array $params = [])
    {
        if (empty($params['app'])) {
            return json(['status' => 00, 'sg' => '获取不到应用名称！']);
        }
        $files = app()::getThemPath($params['app']);
        Cset($params, $files);
        return json(['status' => 200, 'msg' => '提交成功！']);
    }

    /**
     * 获取更新信息方法
     * @param string $app 应用名称
     * @return array 返回包含状态码、消息和数据的 JSON 格式响应
     */
    public static function getUpdateInfos(string $app)
    {
        $files = app()::getThemPath($app);
        $data = Cget($files);
        return (['status' => 200,'msg' => '操作成功', 'data' => array_merge($data, ['app' => $app])]);
    }

    /**
     * 应用主题相关方法
     * @param string $app 应用名称
     * @return array 返回包含状态码和主题列表数据的 JSON 格式响应
     */
    public static function _app_theme(string $app)
    {
        $theme_list = $theme_list_data = [];
        $list = glob(app()::getBasePath().$app.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);

        foreach ($list as $v) {
            $theme_list[] = basename($v);
        }

        foreach ($theme_list as $k => $v) {
            $theme_list_data[$k]['label'] = $v;
            $theme_list_data[$k]['value'] = $v;
        }

        $theme_list_data[] = array('label' => '不选择', 'value' => '');

        return (['status' => 200, 'data' => $theme_list_data]);
    }
}