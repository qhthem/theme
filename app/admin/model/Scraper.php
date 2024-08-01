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
require_once app()::getExtendPath().'Simple/simple_html_dom.php';
use app\admin\model\Content as SC;

class Scraper{
    
    protected $scraper;
    
    protected $url;
    
    /**
     * Scraper 构造函数
     * @param string $url 需要抓取的网页 URL
     * @author zhaosong
     */    
    public function __construct($url){
        $this->url = $url;
        $this->scraper = file_get_html($url);
    }
    
    /**
     * 获取抓取到的标题
     * @param string $title_rule 标题的 CSS 选择器规则
     * @param array $title_html_rule 需要移除的 HTML 标签规则
     * @param bool $is_text 是否获取纯文本内容
     * @return string|false 返回标题字符串或者 false（如果标题为空）
     * @author zhaosong
     */    
    public function get_scraper_title(string $title_rule, array $title_html_rule = [] ,bool $is_text = false)
    {
        if(empty($title_rule)) return json(['status' => 0, 'msg' => '标题规则不能为空']);
        $text = $is_text ? 'plaintext' : 'innertext';
        $elements = $this->scraper->find($title_rule);
        foreach ($elements as $element) {
            $title = $element->$text . PHP_EOL;
        }
        if(empty($title)) return false;
        return empty($title_html_rule) ? $title :$this->tagsToRemove($title,$title_html_rule);
    }
    
    /**
     * 获取抓取到的内容
     * @param string $content_rule 内容的 CSS 选择器规则
     * @param array $content_html_rule 需要移除的 HTML 标签规则
     * @param bool $is_text 是否获取纯文本内容
     * @param int $down_attachment 是否下载附件
     * @param string $id 附件 ID
     * @param string $down_url 附件下载 URL
     * @return string|false 返回内容字符串或者 false（如果内容为空）
     * @author zhaosong
     */    
    public function get_scraper_content($content_rule, $content_html_rule = [] ,$is_text = false ,$down_attachment = 0 , $id = '',$down_url = '')
    {
        if(empty($content_rule)) return json(['status' => 0, 'msg' => '内容规则不能为空']);
        $text = $is_text ? 'plaintext' : 'innertext';
        $elements = $this->scraper->find($content_rule);
        foreach ($elements as $element) {
            $content = $element->$text . PHP_EOL;
        }
        if(empty($content)) return false;
        $content = empty($content_html_rule) ? $content :$this->tagsToRemove($content,$content_html_rule);
        return !empty($down_attachment) ? SC::content_img($content,$id,$down_url):$content;
    }  
    
    /**
     * 获取抓取到的时间
     * @param string $time_rule 时间的 CSS 选择器规则
     * @param array $time_html_rule 需要移除的 HTML 标签规则
     * @return string|false 返回时间字符串或者 false（如果时间为空或者时间规则错误）
     * @author zhaosong
     */    
    public function get_scraper_time(string $time_rule, array $time_html_rule = [])
    {
        if(empty($time_rule)) return json(['status' => 0, 'msg' => '时间规则不能为空']);
        
        $elements = $this->scraper->find($time_rule);
        foreach ($elements as $element) {
            $time = $element->innertext . PHP_EOL;
        }
        if(empty($time)) return false;
        $pattern = '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/';
        if (preg_match($pattern, $time, $matches)){
             $time = $matches[0];
        }
        else {
             return json(['status' => 0, 'msg' => '时间规则错误']);
        }
        return empty($time_html_rule) ? $time :$this->tagsToRemove($time,$time_html_rule);
    }
    
    /**
     * 移除指定的 HTML 标签
     * @param string $html 需要处理的 HTML 字符串
     * @param array $tag 需要移除的 HTML 标签数组
     * @return string 返回处理后的 HTML 字符串
     * @author zhaosong
     */    
    private function tagsToRemove(string $html , array $tag){
        
        $dom = str_get_html($html);
        $tagsToRemove = $tag;
        foreach ($tagsToRemove as $tag) {
            $elements = $dom->find($tag);
            foreach ($elements as $element) {
                $element->outertext = '';
            }
        }
        return $dom->save(); 
    }

}