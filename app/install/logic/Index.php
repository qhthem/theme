<?php
// +----------------------------------------------------------------------
// | 2023-12-18 [ 代码创造未来，思维改变世界。 ]
// +----------------------------------------------------------------------
// | Powered By Astro-程序安装入口逻辑文件
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace app\install\logic;
use app\install\model\install as in;
use Exception;
use PDO;


class Index
{
    /**
     * 安装首页逻辑
     *
     * @author zhaosong
     * @return void
     * @throws Exception
     */
    public static function indexlogic()
    {
        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            throw new Exception('您的php版本过低，不能安装本软件，请升级到8.0.0或更高版本再安装！', 404);
        }
        if (is_file(app()::getConfigPath() . 'install.lock')) {
            throw new Exception("程序已运行安装，如果你确定要重新安装，请先从FTP中删除install.lock！");
        }

        if (!is_file(app()::getBasePath() . 'install/sql/' . 'database.sql')) {
            throw new Exception('缺少数据库文件！', 404);
        }

        include view('install', 'index');
    }

    /**
     * API逻辑
     *
     * @author zhaosong
     * @return array
     */
    public static function apilogic()
    {
        if (is_file(app()::getConfigPath() . 'install.lock')) {
            return['status' => 00,'msg'=>"程序已运行安装，如果你确定要重新安装，请先从FTP中删除install.lock！"];
        }
        cache_type()->delCache('install');
        $data = [
            ['laber' => '操作系统', 'value' => 'Linux', 'status' => php_uname('s'), 'request' => '不限制'],
            ['laber' => 'PHP版本', 'value' => '>=8.0.x', 'status' => PHP_VERSION, 'request' => '8.0'],

            ['laber' => 'pdo', 'value' => '5.x.x', 'status' => class_exists('pdo') ? '可安装' : 'false', 'request' => '安装'],
            ['laber' => '附件上传', 'value' => '2M', 'status' => in::file_uploads(), 'request' => '不限制'],

            ['laber' => '伪静态', 'value' => '开启', 'status' => in::mod_rewrite(), 'request' => '开启'],
            ['laber' => 'SESSION', 'value' => '开启', 'status' => in::session_starts(), 'request' => '开启'],

            ['laber' => 'GD扩展库', 'value' => '开启', 'status' => in::GDVersion(), 'request' => '开启'],
            ['laber' => 'CURL扩展库', 'value' => '开启', 'status' => in::curl_inits(), 'request' => '开启'],

            ['laber' => '配置可写', 'value' => '可写', 'status' => in::checkFolderWritable() ? '可写' : '不可写', 'request' => '可写'],
        ];

        $install = cache_type()->get('install');
        $err = !empty($install['err']) ? $install['err'] : 0;
        return ['status' => 200, 'data' => $data, 'err' => $err];
    }

    /**
     * 获取安装进度
     *
     * @author zhaosong
     * @return array
     */
    public static function progress()
    {
        $progress = cache_type()->get('install_progress');
        $title = empty($progress['title']) ? '获取中...' : $progress['title'];
        $progress = empty($progress['progress']) ? 0 : $progress['progress'];
        return ['status' => 200, 'progress' => (int) $progress, 'title' => $title];
    }

    /**
     * 创建数据
     *
     * @author zhaosong
     * @param array $params 创建数据所需的参数
     * @return array
     */
    public static function createdata($params)
    {
        $install = cache_type()->get('install');
        if (!empty($install['err'])) {
            return ['status' => 00, 'msg' => '环境检查未通过审验，请重新验证！'];
        }

        try {
            $dsn = "mysql:host={$params['dbhost']};dbname={$params['dbuser']};charset=utf8";
            $pdo = new PDO($dsn, $params['dbuser'], $params['dbpw']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            return ['status' => 00, 'msg' => $e->getMessage()];
        }

        $sqldata = file_get_contents(app()::getBasePath() . 'install/sql/' . 'database.sql');
        $sqlFormat = in::sql_split($sqldata, $params['dbprefix']);

        $progress = 0;
        $counts = count($sqlFormat);

        for ($i = 0; $i < $counts; $i++) {
            $sql = trim($sqlFormat[$i]);
            if (empty($sql)) continue;

            if (strstr($sql, 'CREATE TABLE')) {
                preg_match('/CREATE TABLE `([^ ]*)`/', $sql, $matches);
                try {
                    $pdo->exec("DROP TABLE IF EXISTS `$matches[1]`");
                    $pdo->exec($sql);
                    $progress++;
                    $percentage = ($progress / $counts) * 100;
                    cache_type()->set('install_progress', ['progress' => (int) $percentage, 'title' => $matches[1]]);
                    usleep(100000);
                } catch (PDOException $e) {
                    $message = $matches[1] . '，失败';
                    return ['status' => 00, 'msg' => $message];
                }
            } else {
                try {
                    $pdo->exec($sql);
                    $progress++;
                    $percentage = ($progress / $counts) * 100;
                    cache_type()->set('install_progress', ['progress' => (int) $percentage, 'title' => '获取中...']);
                } catch (PDOException $e) {
                    $message = '执行 SQL 语句失败';
                }
            }
        }
        $progress++;
        $percentage = ($progress / $counts) * 100;
        cache_type()->set('install_progress', ['progress' => (int) $percentage, 'title' => '完成']);
        cache_type()->delCache('install_progress');

        env('',null,'create',$params);
        @touch(app()::getConfigPath() . 'install.lock');
        return ['status' => 200, 'msg' => '安装成功！'];
    }
}
