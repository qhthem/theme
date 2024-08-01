<?php
// +----------------------------------------------------------------------
// | 图片处理设置
// +----------------------------------------------------------------------

return [
    // 选择水印类型 为空则不开启
    'images_type' => '',
    // 图片水印路径
    'images_watermark' => ROOT_PATH.'app'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'water'.DIRECTORY_SEPARATOR.'watermark.png',
    // 图片水印宽度
    'images_w' => 300,
    // 图片水印高度
    'images_h' => 300,
    // 水印位置
    'images_pos' => 9,
    // 水印透明度
    'images_opacity' => 100,
    // 文字水印路径
    'images_font' =>ROOT_PATH.'app'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'water'.DIRECTORY_SEPARATOR.'elephant.ttf',
    // 文字水印内容
    'images_content' => 'Hello World',
    // 文字水印颜色
    'images_font_color' => '#ffffff',
    // 文字水印字体大小
    'images_font_size' => 12,
];