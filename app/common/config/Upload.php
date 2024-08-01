<?php
// +----------------------------------------------------------------------
// | 上传设置
// +----------------------------------------------------------------------

return [
    // 上传类
    'upload_type' => '',       
    // 上传文件保存路径
    'upload_path' => app()::getRunPath().'uploads/'.date('Ymd').DIRECTORY_SEPARATOR,
    // 文件获取路径
    'upload_getpath' => DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.date('Ymd').DIRECTORY_SEPARATOR,
    // 上传文件大小限制，默认2MB
    'upload_size' => 2097152,
    // 允许上传的文件后缀
    'upload_ext' => 'jpg,jpeg,png,gif,zip',
];