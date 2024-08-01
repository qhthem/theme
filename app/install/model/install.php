<?php
// +----------------------------------------------------------------------
// | 2023-12-18 [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Powered By Astro-程序安装入口模型文件
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace app\install\model;

class Install
{
    /**
     * 错误计数器
     *
     * @var int
     */
    public static $err = 0;

    /**
     * 增加错误计数
     *
     * @author zhaosong
     * @return void
     */
    public static function _err()
    {
        $num = self::$err++;
        cache_type()->set('install', ['err' => (int) self::$err + 1]);
    }

    /**
     * 检查文件上传功能是否开启
     *
     * @author zhaosong
     * @return string
     */
    public static function file_uploads()
    {
        if (ini_get('file_uploads')) {
            return ini_get('upload_max_filesize');
        } else {
            self::_err();
            return '未开启';
        }
    }

    /**
     * 检查伪静态功能是否开启
     *
     * @author zhaosong
     * @return string
     */
    public static function mod_rewrite()
    {
        $filePath = app()::getRunPath() . '.htaccess';
        if (file_exists($filePath)) {
            return '已开启';
        }
        self::_err();
        return '未开启';
    }

    /**
     * 检查SESSION功能是否开启
     *
     * @author zhaosong
     * @return string
     */
    public static function session_starts()
    {
        if (function_exists('session_start')) {
            return '已开启';
        } else {
            self::_err();
            return '未开启';
        }
    }

    /**
     * 检查CURL功能是否开启
     *
     * @author zhaosong
     * @return string
     */
    public static function curl_inits()
    {
        if (function_exists('curl_init')) {
            return '已开启';
        } else {
            self::_err();
            return '未开启';
        }
    }

    /**
     * 检查GD扩展库是否开启
     *
     * @author zhaosong
     * @return string
     */
    public static function GDVersion()
    {
        $tmp = function_exists('gd_info') ? gd_info() : [];
        if (empty($tmp['GD Version'])) {
            self::_err();
            return '未开启';
        } else {
            return '已开启';
        }
    }

    /**
     * 检查配置文件夹是否可写
     *
     * @author zhaosong
     * @return bool
     */
    public static function checkFolderWritable()
    {
        $folderPath = app()::getConfigPath();
        $files = glob($folderPath . '/*');
        $writable = true;

        foreach ($files as $file) {
            if (!is_writable($file)) {
                $writable = false;
                self::_err();
                break;
            }
        }

        return $writable;
    }

    /**
     * 分割SQL语句
     *
     * @author zhaosong
     * @param string $sql SQL语句
     * @param string $tablepre 表前缀
     * @return array
     */
    public static function sql_split($sql, $tablepre)
    {
        $sql = str_replace('qh_', $tablepre, $sql);
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);

        $sql = str_replace("\r", "\n", $sql);
        $ret = [];
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-') {
                    $ret[$num] .= $query;
                }
            }
            $num++;
        }
        return $ret;
    }
}