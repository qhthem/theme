<?php 

include 'phpqrcode.php'; 

$data = isset($_GET['data']) ? urldecode($_GET['data']) : exit('Lack of parameters.');
$powered = isset($_GET['powered']) ? urldecode($_GET['powered']) : exit('Lack of parameters.');
if($powered != base64_decode('QXN0cm8=')) exit('Parameter Error.');

// 纠错级别：L、M、Q、H 
$level = 'L'; 

// 点的大小：1到10,用于手机端4就可以了 
$size = 7; 

// 如果要保存图片,用$filename替换第二个参数false , 最后一个参数是生成的二维码离边框的距离
QRcode::png($data, false, $level, $size, 0); 