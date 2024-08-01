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
use SimpleXMLElement;
/**
 * 网站地图生成类
 */
class Sitemap
{
    private $urls = array();
    private $domain;
    private $maxUrls;

    /**
     * 构造函数
     *
     * @param string $domain 网站域名
     * @param int $maxUrls 最大URL数量，默认为50000
     */
    public function __construct($domain, $maxUrls = 50000)
    {
        $this->domain = $domain;
        $this->maxUrls = $maxUrls;
    }

    /**
     * 添加URL到地图中
     *
     * @param string $url URL路径
     * @param string $lastModified 最后修改时间，格式为YYYY-MM-DD
     * @param string $changeFrequency 更改频率，如always、hourly、daily、weekly、monthly、yearly、never
     * @param float $priority 优先级，范围为0.0到1.0之间
     */
    public function addUrl($url, $lastModified, $changeFrequency, $priority)
    {
        if (count($this->urls) < $this->maxUrls) {
            $this->urls[] = array(
                'loc' => $url,
                'lastmod' => $lastModified,
                'changefreq' => $changeFrequency,
                'priority' => $priority
            );
        }
    }

    /**
     * 生成网站地图XML文件
     *
     * @return string 网站地图XML内容
     */
    public function buildSitemap()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($this->urls as $url) {
            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', $this->domain . $url['loc']);
            $urlElement->addChild('lastmod', $url['lastmod']);
            $urlElement->addChild('changefreq', $url['changefreq']);
            $urlElement->addChild('priority', $url['priority']);
        }

        return $xml->asXML();
    }

    /**
     * 保存网站地图XML文件
     *
     * @param string $filename 文件名
     */
    public function saveSitemap($filename)
    {
        $sitemap = $this->buildSitemap();
        file_put_contents($filename, $sitemap);
    }

    /**
     * 保存网站地图TXT文件
     *
     * @param string $filename 文件名
     */
    public function saveTxtSitemap($filename)
    {
        $txtSitemap = '';
        foreach ($this->urls as $url) {
            $txtSitemap .= $this->domain . $url['loc'] . PHP_EOL;
        }
        file_put_contents($filename, $txtSitemap);
    }
}
