<?php
// +----------------------------------------------------------------------
// | 监听行为设置
// +----------------------------------------------------------------------
return [
    'hook_sign' => ['method' =>'sign','class'=>'\\app\\member\\controller\\Api','params'=>'sign']
];