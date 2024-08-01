<?php
// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------

return [
    // 日志保存路径
    'log_path' => ROOT_PATH.'app'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR,
    // 日志级别
    'log_level'   => 'debug',
    // 是否自动清理
    'log_auto_clear' => false,
    // 最大日志文件数量
    'log_max_files'     => 30,
];