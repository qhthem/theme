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
use app\admin\model\Handle;
use qhphp\config\Config;
use app\admin\model\CacheManager;
use app\member\model\Other;

class System
{
    
    /**
     * 获取配置文件的逻辑处理
     *
     * @author zhaosong
     * @return array 返回配置数组
     */
    public static function indexlogic()
    {
        $files = App()::getConfigPath() . 'Config.php';
        if (@include($files)) {
            $config_array = include $files;
            return ['status' => 200, 'data' => $config_array];
        }
        
        else {
            return ['status' => 00, 'msg' => '获取配置文件失败，请检查权限！'];
        }
    }
    
    /**
     * 添加配置文件的逻辑处理
     *
     * @author zhaosong
     * @param array $params 需要添加的配置参数
     * @return array 返回操作结果
     */
    public static function addlogic(array $params)
    {
        Config::set($params, 'Config');
        return json(['status' => 200, 'msg' => '更新成功']);
    }
    
    /**
     * 清除缓存的方法
     *
     * @param array $params 包含需要清除的缓存类型的数组
     * @return \qh\response\Json 返回操作结果的JSON对象
     * @author zhaosong
     */
    public static function clear_cache(array $params = [])
    {
        if (empty($params['type'])) {
            return json(['status' => 00, 'msg' => '请选择清除类型']);
        }
    
        foreach ($params['type'] as $item) {
            if ($item == 'clear_template' && empty($params['clear_template'])) {
                return json(['status' => 00, 'msg' => '请选择清除模版缓存类型']);
            }
    
            if (!empty($params['clear_template'])) {
                foreach ($params['clear_template'] as $items) {
                    (new CacheManager())->$item($items);
                }
            } else {
                (new CacheManager())->$item();
            }
        }
    
        return json(['status' => 200, 'msg' => '数据缓存清除成功']);
    }
    
    /**
     * 统计系统面板数组
     *
     * @var array
     */
    
    public static function workbenchlogic()
    {
        /**
         * 初始化数据计数器
         *
         * @var array
         */
        $count = [
            ['label' => '全部内容', 'value' => db('content')->count()],
            ['label' => '模块插件', 'value' => 12],
            ['label' => '登录日志', 'value' => db('admin_login_log')->count()],
            ['label' => '管理员', 'value' => db('admin')->count()],
        ];

        /**
         * 获取当前管理员信息
         *
         * @var array
         */    
        $info = Session()::get('admininfo');
        $logintime = db('admin')->where(['adminid' => $info['adminid']])->value('logintime');
        $loginip = db('admin')->where(['adminid' => $info['adminid']])->value('loginip');
        
        /**
         * 初始化管理员信息
         *
         * @var array
         */
        $manage = [
            ['label' => '用户名', 'value' => $info['adminname']],
            ['label' => '所属角色', 'value' => $info['rolename']],
            ['label' => '上次登录时间', 'value' => !empty($logintime) ? date("Y-m-d H:i:s", $logintime) : '首次登录'],
            ['label' => '上次登录IP', 'value' => !empty($loginip) ? $loginip : '首次登录'],
        ];
        
        /**
         * 初始化服务器信息
         *
         * @var array
         */    
        $soft = [
            ['label' => 'WEB服务器', 'value' => $_SERVER['SERVER_SOFTWARE']],
            ['label' => 'PHP版本', 'value' => PHP_VERSION],
            ['label' => 'MySQL版本', 'value' => db('admin')->version()],
            ['label' => '服务器操作系统', 'value' => php_uname('s')],
            ['label' => '最大上传限制', 'value' => ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'Disabled'],
            ['label' => '最大执行时间', 'value' => ini_get('max_execution_time') . '秒'],
            ['label' => '服务器域名', 'value' => $_SERVER['HTTP_HOST']],
            ['label' => '服务器端口', 'value' => $_SERVER['SERVER_PORT']],
            ['label' => '服务器剩余空间', 'value' => disk_free_space('.') ? Handle::formatBytes(disk_free_space('.')) : '---'],
        ];
        
        /**
         * 初始化系统版本信息
         *
         * @var array
         */    
        $version = [
            ['label' => '系统版本', 'value' => '1.0.2'],
            ['label' => '系统框架', 'value' => 'Astro_Qhphp_' . app()::VERSION],
            ['label' => '前端框架', 'value' => 'Naive UI'],
            ['label' => '系统特色', 'value' => '前后端分离，请求全验证'],
        ];
        
        $rand_menu = []; // 初始化为空数组
        $rand_menu = Cache()->get('rand_menu');
        if(empty($rand_menu)){
            $menu = db('menu')->field('icon,name,data')->where(['parentid' => ['>', 0],'status' => 1])->order('RAND()')->limit(8)->select();
            Cache()->set('rand_menu',$menu,24000);
            $rand_menu = $menu; // 更新为最新查询结果
        }
        
        foreach ($rand_menu as $k => $v) {
            $rand_menu[$k]['icon'] = $rand_menu[$k]['icon'] ? $rand_menu[$k]['icon'] :'Minus';
        }
        
        /**
         * 组合数据并返回
         *
         * @var array
         */    
        $data = ['status' => 200, 'data' => ['count' => $count, 'manage' => $manage, 'soft' => $soft, 'version' => $version,'menu'=>$rand_menu]];
    
        return $data;
    }
    
    
    /**
     * 获取登录日志数据并返回格式化的数组
     *
     * @return array 包含日期和数量的数组
     */
    public static function myChartlogic() {
        $starttime = strtotime(date('Y-m-d'))-10 * 24 * 3600;
        $endtime = strtotime(date('Y-m-d'));
        $where = "logintime > $starttime";
        $data = db('admin_login_log')->field("COUNT(*) AS num,FROM_UNIXTIME(logintime, '%Y-%m-%d') AS gap")->where($where)->group('gap')->select();
        $arr = array();
        foreach ($data as $val) {
            $arr[$val['gap']] = intval($val['num']);
        }
        for ($i=$starttime; $i<=$endtime; $i = $i+24 * 3600) {
            $num = isset($arr[date('Y-m-d',$i)]) ? $arr[date('Y-m-d',$i)] : 0;
            $result['day'][] = date('Y-m-d',$i);
            $result['num'][] = $num;
        }
        return $result;
    }
    
    /**
     * 发送邮件验证码
     * @return void
     * @author zhaosong
     */    
    public static function send_email($email)
    {
        if(empty($email)) return json(['status' => 00, 'msg' => '发送邮件对象不能为空！']);
        Other::send_email_code($email,false);
    }
    
}