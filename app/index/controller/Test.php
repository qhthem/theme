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
namespace app\index\controller;
use qhphp\session\Session;

class Test{
    
    public function index()
    {
        showmsg('由于没有找到控制器，自动定位到空控制器！',0);
        // include view('index', 'test1');
    }


    public function indexs()
    {
        // 设置头部，以便浏览器输出图片
        header('Content-Type: image/png');
        
        // 加载背景图片
        $background = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/static/them/images/wenz.png');
        
        // 加载要融合的图片
        $overlay = imagecreatefrompng('https://www.astrocms.cn/uploads/20240510/f56d4fc484dd900529747b7dd8cfa075.png');
        
        // 设置标题颜色和字体
        $titleColor = imagecolorallocate($background, 0, 0, 0);
        $fontPath = $_SERVER['DOCUMENT_ROOT'].'/static/fonts/AlimamaFangYuanTiVF-Thin.ttf'; // 字体文件路径，例如：'arial.ttf'
        $fontSize = 40; // 字体大小
        $title = "可能是WordPress中首创的自定义表单支付功能演示";
        $titleX = 360; // 标题的X坐标
        $titleY = 250; // 标题的Y坐标
        
        // 在背景图片上添加标题
        imagettftext($background, $fontSize, 0, $titleX, $titleY, $titleColor, $fontPath, $title);
    
        // 获取背景图片的尺寸
        $bgWidth = imagesx($background);
        $bgHeight = imagesy($background);
    
        // 获取融合图片的尺寸
        $overlayWidth = imagesx($overlay);
        $overlayHeight = imagesy($overlay);
    
        // 计算融合图片的位置（中下）
        $overlayX = ($bgWidth - $overlayWidth) / 2;
        $overlayY = $bgHeight - $overlayHeight - 250;
    
        // 将融合图片复制到背景图片上
        imagecopy($background, $overlay, round($overlayX), round($overlayY), 0, 0, (int)$overlayWidth, (int)$overlayHeight);
        $bgColor = imagecolorallocate($overlay, 255, 255, 255); // 背景颜色
        $borderColor = imagecolorallocate($overlay, 0, 0, 0); // 边框颜色
        $borderThickness = 10; // 边框厚度
        $borderRadius = 20; // 圆角半径
        // 绘制黑色边框（厚度为10px，圆角半径为20px）
        for ($i = 0; $i < $borderThickness; $i++) {
            // 绘制边框
           imagerectangle($background, (int)$overlayX - $i, (int)$overlayY - $i, (int)$overlayX + (int)$overlayWidth + $i - 1, (int)$overlayY + (int)$overlayHeight + $i - 1, $borderColor);
        }
    
        // 输出图片
        imagejpeg($background);
    
        // 销毁图片资源
        imagedestroy($background);
        imagedestroy($overlay); 
    
    }
}