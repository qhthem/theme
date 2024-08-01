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
namespace app\admin\model;
use qhphp\logs\Log;
use app\admin\model\Handle as del;
class CacheManager
{
    /**
     * CacheManager 构造函数
     *
     * @author zhaosong
     */
    public function __construct()
    {
    }

    /**
     * 清除模板缓存
     *
     * @param string $dir_path 模板缓存目录路径
     * @author zhaosong
     */
    public function clear_template(string $app_path)
    {
        if($app_path == 'all_clear'){
            foreach ($this->_app_clear() as $v){
                $dir_path = app()::getRuntimePath().$v;
                del::del_target_dir($dir_path, true);
            }
        }
        else{
            $dir_path = app()::getRuntimePath().$app_path;
            $this->rrmdir($dir_path);
        }
        
    }
    
    /**
     * _app_clear 方法
     * @author zhaosong
     * @return array 返回清理后的主题列表
     */
    protected function _app_clear()
    {
        $theme_list = [];
        $list = glob(app()::getRuntimePath().'*', GLOB_ONLYDIR);
    
        foreach ($list as $v) {
            $theme_list[] = basename($v);
        }
    
        return $theme_list;
    }

    /**
     * 清除缓存
     *
     * @author zhaosong
     */
    public function clear_cache()
    {
        Cache()->clear();
        cache_type()->clearCache();
        $stores = C('stores')[C('cache_default')];
        del::del_target_dir($stores['path'], true);
    }

    /**
     * 清除会话缓存
     *
     * @author zhaosong
     */
    public function clear_sessions()
    {
        Session()::clear(C('session_prefix'));
    }
    
    
    /**
     * 清除错误日志
     *
     * @author zhaosong
     */
    public function clear_log()
    {
        Log::clear();
    }

    /**
     * 递归删除目录及其所有内容
     *
     * @param string $dir 要删除的目录路径
     * @author zhaosong
     */
    private function rrmdir(string $dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . '/' . $object)) {
                        $this->rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}