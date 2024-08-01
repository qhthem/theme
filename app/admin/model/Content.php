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
/**
 * 内容处理类
 *
 * @author zhaosong
 */
use app\admin\model\Handle; 
use app\admin\logic\Content as Contentlogics;
class Content 
{
    /**
     * @author: zhaosong
     * @function 处理数组数据
     * @param array $params 传入的数组参数
     * @return array 处理后的数组
     */
    public static function arrayprocessing(array $params)
    {
        $admininfo = Session()::get('admininfo');
        
        $params['inputtime'] = !empty($params['inputtime']) ? removeLastTenCharacters($params['inputtime']):time();
        $params['updatetime'] = time();
        $params['description'] = empty($params['description']) ? Handle::truncateString($params['content'],250) : $params['description'];
        $params['username'] = $admininfo['adminname'];
        $params['userid'] = $admininfo['adminid'];
        $params['catid'] = intval($params['catid']);
        $params['click'] = !empty($params['click']) ? $params['click'] :rand(100,200);
        $params['status'] = isset($params['status']) ? intval($params['status']) : 0;
        
        return $params;       
    }
    
    /**
     * @author: zhaosong
     * @function 获取默认模型
     * @param int $modelid 模型ID
     * @param bool $type 类型
     * @return mixed 返回模型表名或模型ID
     */
    public static function getdefaultmodel(int $modelid,bool $type = true)
    {
        $where = empty($modelid) ? ['isdefault'=>1] :['modelid'=>$modelid];
        $content_tabname = self::modelmodel($where, $type ? 'tablename':'modelid');
        return $content_tabname;
    }
    
    /**
     * @author: zhaosong
     * @function 根据参数获取模型信息
     * @param mixed $param 参数
     * @param string $field 字段
     * @return mixed 返回模型信息
     */
    public static function modelmodel($param, $field)
    {
        return get_model_where($param, $field);
    }
    
    /**
     * @author: zhaosong
     * @function 移动模型
     * @param string $ids 内容ID
     * @param int $catid 栏目ID
     * @return bool 返回处理结果
     */
    public static function movemodel($ids, int $catid)
    {
        foreach (explode(',',$ids)  as $val){
            $modelid = db('content')->where(['id'=>$val])->value('modelid');
            $content_tabname = self::getdefaultmodel($modelid);
            
            db($content_tabname)->where(['id'=>$val])->update(['catid'=>$catid]);
            db('content')->where(['id'=>$val])->update(['catid'=>$catid]);
        }
        
        return true;
        
    }
    
    /**
     * @author: zhaosong
     * @function 复制模型
     * @param string $ids 逗号分隔的ID字符串
     * @param int $catid 目标栏目ID
     * @return bool 成功返回true，失败返回false
     */
    public static function copymodel($ids, int $catid)
    {
        foreach (explode(',', $ids) as $val) {
            $modelid = db('content')->where(['id' => $val])->value('modelid');
            $content_tabname = self::getdefaultmodel($modelid);
            $res = db($content_tabname)->where(['id' => $val])->find();
            $res['id'] = '';
            $res['modelid'] = $modelid;
            $res['catid'] = $catid;
            $res['click'] = rand(100, 150);
    
            $res['id'] = db($content_tabname)->insertGetId($res);
            $url = Contentlogics::categorylogic(['catid' => $catid], 'pclink') . $res['id'] . '.html';
            db($content_tabname)->where(['id' => $res['id']])->update(['url' => $url]);
            $res['url'] = $url;
            db('content')->insertGetId($res);
        }
    
        return true;
    }
    
    /**
     * @author: zhaosong
     * @function 更新模型状态
     * @param string $ids 逗号分隔的ID字符串
     * @param int $status 目标状态
     * @return bool 成功返回true，失败返回false
     */
    public static function statusmodel($ids, int $status)
    {
        foreach (explode(',', $ids) as $val) {
            $content_tabname = Contentlogics::getcontentmodellogic($val);
            db($content_tabname)->where(['id' => $val])->update(['status' => $status]);
            db('content')->where(['id' => $val])->update(['status' => $status]);
        }
    
        return true;
    }
    
    /**
     * @author: zhaosong
     * @function 百度推送模型
     * @param string $url 推送的URL
     * @return mixed 成功返回true，失败返回false
     */
    public static function baiduPushmodel($ids,$modelid)
    {
        if (empty(C('baidu_push_token'))) {
            return ['status' => 100, 'msg' => '百度推送token不可以为空！'];
        }
        
        foreach ($modelid as $val){
            $modelid = $val['value'];
        }
        
        $apiKey = C('baidu_push_token'); // 替换为您的百度推送 API 密钥
        $api = 'http://data.zz.baidu.com/urls?site='.get_Domain().'&token=' . $apiKey; // 替换为您的百度推送 API URL
        
        $tablname = self::getdefaultmodel($modelid);
        
        foreach (explode(",", $ids) as $val){
            $url = db($tablname)->where(['id'=>$val,'is_push'=>0])->value('url');
            if(empty($url)){
                return json(['status' =>0 ,'msg' => '你所选的选项中包含了已经提交的数据']);
            }
            $ch = curl_init();
            $options =  array(
                CURLOPT_URL => $api,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => get_Domain().$url,
                CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
            );
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            db($tablname)->where(['id'=>$val])->update(['is_push'=>1]);
        }
        
        if ($result === false) {
            return false;
        }
    
        $response = json_decode($result, true);
        
        if(empty($response['success'])){
            return json(['status' =>0 ,'msg' => $response['message']]);
        }
        else{
            return json(['status' =>200 ,'msg' => '推送成功！当天剩余的可推送url条数'.$response['remain']]);
        }
        
    }
    
    /**
     * @author: zhaosong
     * @function 处理标签
     * @param int $catid 栏目ID
     * @param array $tags 标签数组
     * @param int $aid 文章ID
     * @param int $modelid 模型ID
     * @return void
     */
    public static function tag_dispose($catid, array $tags, $aid, $modelid)
    {
        db('tag')->where(['modelid' => $modelid, 'aid' => $aid])->delete();
        $params = [];
        foreach ($tags as $val) {
            $params['tag'] = $val;
            $params['modelid'] = $modelid;
            $params['catid'] = $catid;
            $params['inputtime'] = time();
            $params['aid'] = $aid;
            db('tag')->insert($params);
        }
    }
    
    /**
     * @author: zhaosong
     * @function 获取内容中的第一张图片的URL
     * @param string $content 内容字符串
     * @return mixed 成功返回图片URL，失败返回false
     */
    public static function get_first_image_url($content)
    {
        // 使用正则表达式匹配第一张图片的 <img> 标签
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $matches);
        // 检查是否找到了匹配项
        if (!empty($matches['src'])) {
            return $matches['src'];
        } else {
            return false;
        }
    }
    
    /**
     * @author: zhaosong
     * @function 处理内容数据
     * @param array $content 内容数组
     * @return string 返回处理后的内容字符串
     */
    public static function content_disposemodel($content)
    {
        $is_array = false;
        foreach ($content as $val) {
            if (is_array($val)) $is_array = true;
            break;
        }
    
        if (!$is_array) return implode(',', $content);
    }
    
    /**
     * 为包含 <pre> 标签的 HTML 字符串添加新的 class 属性值。
     *
     * @param string $content 包含 <pre> 标签的 HTML 字符串
     * @return string 修改后的 HTML 字符串
     * @author zhaosong
     */
    public static function addClassesToPreTag(string $content): string
    {
        $pattern = '/<pre(?:\s+class="([^"]+)")?/';
        $result = preg_replace_callback($pattern, function ($matches) {
            $existingClasses = $matches[1] ?? '';
            $newClasses = 'moe-highlightjs line-numbers mac-theme';
            // 检查是否存在 language- 前缀
            if (strpos($existingClasses, 'language-') === 0) {
                $existingClasses .= " $newClasses";
            } else {
                $existingClasses = "$newClasses $existingClasses";
            }
    
            return "<pre class=\"$existingClasses\"";
        }, $content);
    
        return $result;
    }  
    
    /**
     * 移除 img 标签中的 width、height 和 align 属性
     *
     * @param string $html 包含 img 标签的 HTML 字符串
     * @return string 处理后的 HTML 字符串，已移除 width、height 和 align 属性
     * @author zhaosong
     */
    public static function remove_img_attributes(string $html): string {
        // 使用正则表达式匹配并删除 width、height 和 align 属性
        $patterns = array(
            '/width="\d+"/i',
            '/height="\d+"/i',
            '/height="auto"/i',
            '/align="(left|right|center)"/i'
        );
        $replacements = array('', '', '', '');
        $result = preg_replace($patterns, $replacements, $html);
        return $result;
    }

    /**
     * 更新 img 标签的 title 和 alt 属性
     *
     * @param string $html 包含 img 标签的 HTML 字符串
     * @param string $title 新的 title 和 alt 属性值
     * @return string 处理后的 HTML 字符串，已更新 title 和 alt 属性
     * @author zhaosong
     */
    public static function updateImgAttributes(string $html, string $title): string {
        $html = preg_replace('/alt="\s*"/i', '', $html);
        // 删除空的 title 属性
        $html = preg_replace('/title="\s*"/i', '', $html);
        $pattern = '/<img\s+([^>]+)>/i';
        $result = preg_replace_callback($pattern, function ($matches) use ($title) {
            $attributes = $matches[1];
            // 删除已存在的 title 和 alt 属性
            $attributes = preg_replace('/title="([^"]+)"/i', '', $attributes);
            $attributes = preg_replace('/alt="([^"]+)"/i', '', $attributes);
            // 添加新的 title 和 alt 属性
            $attributes .= ' title="' . $title . '" alt="' . $title . '"';
            return '<img ' . $attributes . '>';
        }, $html);
        return $result;
    }
    
    /**
     * 替换内容中的图片链接
     * @param string $html
     * @param int $id
     * @return string
     */
    public static function content_img(string $html, int $id ,string $down_url = '')
    {
        // 匹配所有的图片标签;
        preg_match_all('/<img[^>]+src\s*=\s*"([^">]+)"/i', $html, $matches);
        // 遍历每个 <img> 标签
        foreach ($matches[1] as $image) {
            if(!empty($down_url)){
                $newSrc = self::downloadAndReplaceImage($down_url.$image, $id);
                $html = str_replace($image, $newSrc, $html);
            }
            // 判断图片链接是否以 "http://" 或 "https://" 开头
            if (self::startsWith($image, 'http://') || self::startsWith($image, 'https://')) {
                // 下载图片并替换链接
                $newSrc = self::downloadAndReplaceImage($image, $id);
                // 更新 <img> 标签的 src 属性值
                $html = str_replace($image, $newSrc, $html);
            }
        }
        // 返回替换后的 HTML 内容
        return $html;
    }

    /**
     * 判断字符串是否以指定前缀开头
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function startsWith(string $haystack, string $needle)
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    /**
     * 下载并替换图片链接
     * @param string $src
     * @param int $id
     * @return string
     */
    public static function downloadAndReplaceImage(string $src, int $id )
    {
        $outputDirectory = app()::getRunPath() . '/scraper/' . $id;
        // 创建文件夹
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }
        $filename = basename($src);
        // 拼接本地保存路径
        $localPath = $outputDirectory . '/' . $filename;
        // 下载图片并保存到本地
        @file_put_contents($localPath, file_get_contents($src));
        // 返回本地图片链接
        return str_replace(app()::getRunPath(), '', $localPath);
    }

    /**
     * 删除内容中的图片文件夹
     * @param string $folder
     */
    public static function content_img_del(string $folder)
    {
        if (is_dir($folder)) {
            $files = glob($folder . '/*');
            foreach ($files as $file) {
                is_dir($file) ? self::content_img_del($file) : unlink($file);
            }
            rmdir($folder);
        }
    }
    
}