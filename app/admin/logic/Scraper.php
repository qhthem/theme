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
use app\admin\model\Scraper as Scrapermodel;

class Scraper{
    
    /**
     * 获取采集节点列表
     * @author zhaosong
     * @param array $params 请求参数，包括分页和排序信息
     * @return array 返回采集节点列表和状态信息
     */
    public static function indexlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 8;
        $page = !empty($params['page']) ? $params['page'] : 1;
        
        $res = db('collection_node')->field('nodeid,lasttime,uft,name')->order('nodeid DESC')->paginate($page, $limit);
        $res['data'] = array_map(function($item) {
            $item['lasttime'] = !empty($item['lasttime']) ? date("Y-m-d H:i:s", $item['lasttime']):'从未采集';
            return $item;
        }, $res['data']);

        $data['status'] = 200;
        $data['data'] = $res;
        return $data;        
    }
    
    /**
     * 添加采集节点
     * @author zhaosong
     * @param array $params 请求参数，包括节点名称、目标站等信息
     * @return array 返回操作状态和信息
     */
    public static function addlogic(array $params)
    {
        if(empty($params['name'])) return ['status' => 0, 'msg' => '节点名称不能为空！'];
        if(empty($params['urlpage'])) return ['status' => 0, 'msg' => '目标站不能为空！'];
        
        foreach ($params as $key =>$array){
            if(is_array($params[$key])){
                $params[$key] = addslashes(Handle::array2string($array));
            }
        }
        
        db('collection_node')->insert($params);
        return ['status' => 200, 'msg' => '添加成功'];
     }
     
    /**
     * 获取采集节点更新信息
     * @author zhaosong
     * @param int $nodeid 采集节点ID
     * @return array 返回采集节点更新信息和状态信息
     */
    public static function getUpdateInfoslogic(int $nodeid)
    {
        if (empty($nodeid)) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('collection_node')->where(['nodeid' => $nodeid])->find();
    
        $res = self::convertHtmlRulesToArray($res);
        if(!empty($res['rand_time_range'])){
            $res['rand_time_range'][0] = (int) $res['rand_time_range'][0];
            $res['rand_time_range'][1] = (int) $res['rand_time_range'][1];
        }
        else{
            unset($res['rand_time_range']);
        }
        
        return ['status' => 200, 'data' => $res];
    }
    
    /**
     * 将HTML规则字符串转换为数组
     * @author zhaosong
     * @param array $res 包含HTML规则字符串的数组
     * @return array 返回转换后的数组
     */
    private static function convertHtmlRulesToArray($res) {
        $htmlRules = ['title_html_rule', 'time_html_rule', 'content_html_rule','rand_time_range'];
    
        foreach ($htmlRules as $rule) {
            $res[$rule] = $res[$rule] ? Handle::string2array($res[$rule]) : [];
        }
    
        return $res;
    }
     
    /**
     * 更新采集节点信息
     * @author zhaosong
     * @param array $params 请求参数，包括节点名称、目标站等信息
     * @return array 返回操作状态和信息
     */
    public static function updatelogic(array $params){
        if(empty($params['name'])) return ['status' => 0, 'msg' => '节点名称不能为空！'];
        if(empty($params['urlpage'])) return ['status' => 0, 'msg' => '目标站不能为空！'];
        
        foreach ($params as $key =>$array){
            if(is_array($params[$key])){
                $params[$key] = addslashes(Handle::array2string($array));
            }
        }
        db('collection_node')->where(['nodeid'=>$params['nodeid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 删除采集节点
     * @author zhaosong
     * @param string $nodeid 采集节点ID，多个ID用逗号分隔
     * @return array 返回操作状态和信息
     */
    public static function deletelogic($nodeid)
    {
        if (empty($nodeid)) return ['status' => 0, 'msg' => '参数错误'];
        db('collection_node')->destroy(explode(',',$nodeid));
        return (['status' => 200, 'msg' => '操作成功']);
    }
    
    /**
     * 采集节点测试
     * @author zhaosong
     * @param int $nodeid 采集节点ID
     * @return array 返回采集节点测试结果和状态信息
     */
    public static function scraper_test(int $nodeid){
        if (empty($nodeid)) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('collection_node')->where(['nodeid' => $nodeid])->find();
        
        if(empty($res['title_rule']) || empty($res['content_rule'])){
            return ['status' => 0, 'msg' => '文章规则模块没有填写完整'];
        }
        
        if(!empty($res['type'])){
            $url = str_replace('(*)', $res['page_start'], $res['urlpage']);
        }else{
            $url = $res['urlpage'];
        }
        
        $htmlRules = new Scrapermodel($url);
        
        $title = $htmlRules->get_scraper_title($res['title_rule']);
        $content = $htmlRules->get_scraper_content($res['content_rule'],
        Handle::string2array($res['content_html_rule']),false,$res['down_attachment'],$res['nodeid'],$res['down_url']);
        return ['data' =>['title' => $title, 'content' => $content ,'name' =>$res['name']] , 'status' => 200];
    }
    
    /**
     * 获取采集进度
     * @author zhaosong
     * @return array 返回采集进度和状态信息
     */
    public static function scraper_progress()
    {
        $progress = cache_type()->get('scraper_progress');
        $progress = empty($progress['progress']) ? 0 : $progress['progress'];
        return ['status' => 200, 'Progress' => (int) $progress];
    }
    
    /**
     * 采集网址
     * @author zhaosong
     * @param int $nodeid 采集节点ID
     * @return array 返回采集网址操作状态和信息
     */    
    public static function scraper_url(int $nodeid){
        if (empty($nodeid)) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('collection_node')->where(['nodeid' => $nodeid])->find();
        
        if(empty($res) || empty($res['urlpage'])){
            return ['status' => 0, 'msg' => '网址配置不能为空！'];
        }
        
        if(empty($res['title_rule'])){
            return ['status' => 0, 'msg' => '文章规则模块没有填写完整'];
        }
        
		if(!empty($res['type'])){
			$url = [];
			for ($i = $res['page_start']; $i <= $res['page_end']; $i = $i + $res['par_num']) {
				$url[] = str_replace('(*)', $i, $res['urlpage']);
			}
		}else{
			$url[0] = $res['urlpage'];
		}
		
		$collection_content = db('collection_content');
		$title_rule = !empty($res['title_html_rule']) ? Handle::string2array($res['title_html_rule']): [];
        $scraper = [];
        // 初始化进度条
        $progress = 0;
        $total = count($url);
        foreach($url as $v){
            $scraper['nodeid'] = $nodeid;
            $scraper['status'] = 0;
            $scraper['url'] = $v;
            $htmlRules = new Scrapermodel($v);
            $title = $htmlRules->get_scraper_title($res['title_rule'],$title_rule);
            $scraper['title'] = $title;
            $collection_content->insert($scraper);
            $progress++;
            $percentage = ($progress / $total) * 100;
            cache_type()->set('scraper_progress', ['progress'=>(int) $percentage]);
        }
        cache_type()->delCache('scraper_progress');
        return ['status' => 200, 'msg' => '采集网址成功新增'.$total.'条数据！'];
    }
    
    /**
     * 采集内容
     * @author zhaosong
     * @param int $nodeid 采集节点ID
     * @return array 返回采集内容操作状态和信息
     */    
    public static function scraper_content(int $nodeid)
    {
        $collection_content = db('collection_content');
        $list = $collection_content->field('id, url')
        ->where(['nodeid' => $nodeid, 'status' => 0])->order('id DESC')->select();
        
        $res = db('collection_node')
        ->field('content_html_rule,content_rule,time_rule,time_html_rule,rand_time,rand_time_range,down_attachment,down_url')
        ->where(['nodeid' => $nodeid])->find();
        
        if (empty($nodeid) ||empty($list) || empty($res)) return ['status' => 0, 'msg' => '参数错误'];
        
        $content_html_rule = !empty($res['content_html_rule']) ? Handle::string2array($res['content_html_rule']): [];
        $scraper = [];
        // 初始化进度条
        $progress = 0;
        $total = count($list);
        foreach($list as $v){
            $htmlRules = new Scrapermodel($v['url']);
            $content = 
            $htmlRules->get_scraper_content($res['content_rule'],$content_html_rule,false,$res['down_attachment'],$v['id'],$res['down_url']);
            $scraper['content'] = addslashes($content);
            $scraper['status'] = 1;
            $time = self::scraper_time($res,$v['url']);
            $scraper['inputtime'] = $time;
            $collection_content->where(['id' => $v['id']])->update($scraper);
            $progress++;
            $percentage = ($progress / $total) * 100;
            cache_type()->set('scraper_progress', ['progress'=>(int) $percentage]);
        }
        db('collection_node')->where(['nodeid' => $nodeid])->update(['lasttime'=>time()]);
        cache_type()->delCache('scraper_progress');
        
        return ['status' => 200, 'msg' => '采集内容成功新增'.$total.'条数据！'];
    }
    
    /**
     * 获取采集时间
     * @author zhaosong
     * @param array $res 采集节点信息
     * @param string $url 采集网址
     * @return int 返回采集时间
     */    
    private static function scraper_time(array $res, string $url)
    {
        $data_time = '';
        $htmlRules = new Scrapermodel($url);
        if(!empty($res['time_rule']) && empty($res['rand_time'])){
            $time_html_rule = !empty($res['time_html_rule']) ? Handle::string2array($res['time_html_rule']): [];
            $data_time = $htmlRules->get_scraper_time($res['time_rule'],$time_html_rule);
            $data_time = strtotime($data_time);
        }
        else if(!empty($res['rand_time'])){
            $timestamp = Handle::string2array($res['rand_time_range']);
            $min_timestamp = $timestamp[0];
            $max_timestamp = $timestamp[1];
            $random_timestamp = rand($min_timestamp, $max_timestamp);
            $data_time = !empty($res['rand_time']) ? $random_timestamp:time();
        }
        
        return  $data_time;
        
    }    
    
}