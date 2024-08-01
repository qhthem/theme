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
namespace app\index\model;

class ContentList
{
    /**
     * 生成侧边栏列表 HTML
     *
     * @param array $data 数据
     * @author zhaosong
     */
    public static function grid_sidebar(array $data)
    {
        $html = ''; // 初始化 $html 变量
        foreach ($data as $v) {
            $html .= '<li class="post-list-item item-">
                <div class="item-in box qh-radius">
                    <div class="post-thumbnail" style="padding-top: 61.8%;">
                        <a href="' . $v['url'] . '" rel="nofollow" class="thumb-link">
                            <img data-src="' . get_thumb($v['thumb']) . '" src="' . get_thumb($v['thumb']) . '" alt="' . $v['title'] . '" class="post-thumb w-h lazyload entered"></a>
                    </div>
                    <div class="post-info">
                        <h2 class="text-ellipsis">
                            <a href="' . $v['url'] . '">' . $v['title'] . '</a></h2>
                        <p class="post-excerpt text-ellipsis">' . $v['description'] . '</p>
                        <div class="post-info-buttom">
                            <a href="/user/' . $v['userid'] . '" rel="nofollow" class="post-user">
                                <div class="user-avatar">
                                    <img src="' . get_avatar($v['userid']) . '" class="avatar-face w-h"></div>
                                <span class="user-name">' . get_userinfo($v['userid'], 'nickname') . '</span></a>
                            <span class="post-views qh-flex">
                                <i class="ri-eye-line"></i>' . $v['click'] . '</span>
                            <span class="post-like qh-flex">
                                <i class="ri-thumb-up-line"></i>' . $v['likes'] . '</span>
                        </div>
                    </div>
                </div>
            </li>';
        }

        return $html;
    }

    /**
     * 生成网格列表 HTML
     *
     * @param array $data 数据
     * @param int   $modelid 模型ID
     * @author zhaosong
     */
    public static function grid_qh(array $data, int $modelid)
    {
        $html = ''; // 初始化 $html 变量

        foreach ($data as $v) {
            $html .= '<li id="' . $v['id'] . '" class="post-list-item item-">
            <div class="item-in box qh-radius">
                <div class="post-module-thumb">
                    <div class="qh-radius post-thumbnail" style="padding-top: 100%;">
                        <a href="' . $v['url'] . '" rel="nofollow" class="thumb-link">
                            <img data-src="' . get_thumb($v['thumb']) . '" src="' . get_thumb($v['thumb']) . '" alt="' . $v['title'] . '"
                            class="post-thumb w-h qh-radius lazyload"></a>
                    </div>
                </div>
                <div class="post-info">
                    <h2 class="text-ellipsis">
                        <a href="' . $v['url'] . '">' . $v['title'] . '</a></h2>
                    <p class="post-excerpt text-ellipsis">' . $v['description'] . '</p>
                    <div class="post-info-buttom">
                        <div class="buttom-left">
                            <a href="/user/' . $v['userid'] . '" rel="nofollow" class="post-user">
                                <div class="user-avatar">
                                    <img src="' . get_avatar($v['userid']) . '" class="avatar-face w-h"></div>
                                <span class="user-name">' . get_userinfo($v['userid'], 'nickname') . '</span></a>
                        </div>
                        <div class="buttom-right qh-flex">
                            <span class="post-date qh-flex">
                                <i class="ri-time-line"></i>
                                <time class="qh-timeago">' . $v['inputtime'] . '</time></span>
                            <span class="post-views qh-flex">
                                <i class="ri-eye-line"></i>' . $v['click'] . '</span>
                            <span class="comment qh-flex">
                                <i class="ri-message-3-line"></i>' . get_comment_total($v['id'], $modelid) . '</span>
                        </div>
                    </div>
                </div>
            </div>
          </li>';
        }

        return $html;
    }

    /**
     * 生成瀑布流列表 HTML
     *
     * @param array $data 数据
     * @author zhaosong
     */
    public static function grid_waterfall(array $data)
    {
        $html = ''; // 初始化 $html 变量
        foreach ($data as $v) {
            $html .= '<li id="item-' . $v['id'] . '" class="post-list-item item-">
            <div class="item-in">
                <div class="post-module-thumb">
                    <div class="post-thumbnail qh-radius" style="padding-top: ' . self::get_thumb_size($v['thumb']) . ';">
                        <a href="' . $v['url'] . '" rel="nofollow" class="thumb-link">
                            <img data-src="' . get_thumb($v['thumb']) . '" src="' . get_thumb($v['thumb']) . '" alt="' . $v['title'] . '" class="post-thumb w-h qh-radius lazyload"></a>
                    </div>
                </div>
                <a href="' . $v['url'] . '" class="post-info">
                    <h2 class="text-ellipsis">' . $v['title'] . '</h2></a>
                <div class="post-module-badges">
                    <a href="/" class="badge-item no-hover">' . $v['catid'] . '</a></div>
            </div>
        </li>';
        }

        return $html;
    }

    /**
     * 生成空列表 HTML
     *
     * @author zhaosong
     */
    public static function emptys()
    {
        $html = '<div class="qh-empty qh-radius box" style="min-height: 350px;">
          <img data-src="/static/them/images/Nodata-bro.svg" src="Nodata-bro.svg" class="empty-img lazyload" style="width: 200px;">
          <p class="empty-text em14">暂无内容</p></div>
          <style>.col-sm-9 ul{display: flex;}.author-page-content ul{grid-template-columns: repeat(1, minmax(0, 1fr))!important;}';
        return $html;
    }

    /**
     * 获取缩略图尺寸
     *
     * @param string $images 图片路径
     * @return string 图片尺寸百分比
     * @author zhaosong
     */
    public static function get_thumb_size($images)
    {
        $images = app()::getRunPath() . $images;
        list($width, $height) = getimagesize($images);
        $height_percentage = ($height / $width) * 100;
        $rounded_height_percentage = round($height_percentage);
        return $rounded_height_percentage . '%';
    }
}