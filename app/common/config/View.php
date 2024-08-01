<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板目录名
    'view_dir_name' => 'view',
    // 模板后缀
    'view_suffix'   => '.html',
    // 模板文件名分隔符
    'view_depr'     => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'     => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'       => '}',
    // 预先加载的标签库
    'taglib_pre_load'  => 'extend\taglib\Tag',
    //  标签替换
    'tpl_replace_string' => ["STATIC_URL" =>"/static/",]
	
];
