<?php
// +----------------------------------------------------------------------
// | QHPHP [ 代码创造未来，思维改变世界。 ] 时间格式获取类
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 https://www.astrocms.cn/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ZHAOSONG <1716892803@qq.com>
// +----------------------------------------------------------------------
namespace extend\traverse;
/**
 * Traverse 类用于处理和转换数据。
 * 
 * @author zhaosong
 */
class Traverse
{
    // 时间字段列表
    private $time_field = [
        "inputtime", "updatetime", 'logintime', 'addtime', 
        'create_times', 'uploadtime', 'creat_time', 'create_time'
    ];
    
    // 布尔字段列表
    private $bool_field = ["status"];
    
    // 模型ID字段列表
    private $modelid_field = ["modelid"];
    
    // 用户ID字段列表
    private $userid_field = ["userid"];
    
    // 用户图片字段列表
    private $userpic_field = ["userpic"];
    
    /**
     * 工厂方法，用于处理传入的数据数组。
     * 
     * @param array $params 需要处理的数据数组
     * @param array $custom 自定义处理规则
     * @param string $false 用于控制是否处理用户ID字段的标志
     * @return array 处理后的数据数组
     */
    public function factory(array $params, array $custom = [], $false = '')
    {
        foreach ($params as $d =>$data){
            foreach ($params[$d] as $k => $v){
                // var_dump($params[$k]);
                if (in_array($k, $this->time_field)) {
                    $params[$d][$k] = $this->procetime($v,false);
                }
                
                if (in_array($k, $this->bool_field)) {
                    $params[$d][$k] = $this->procebool($v);
                }
                if(!empty($custom)){
                    if (in_array($k, explode(',',$custom['field']))) {
                        $params[$d][$k] = $custom['value'];
                    }
                }
                
                if (in_array($k, $this->modelid_field)) {
                    $params[$d][$k] = $this->procemodel($v);
                }
                
                if (in_array($k, $this->userid_field) && $false !== 'userid') {
                    $params[$d][$k] = $this->proceuserinfo($v);
                }
                
                if (in_array($k, $this->userpic_field)) {
                    $params[$d][$k] = $this->proceuserpic($v);
                }
                
            }
        }
        
        return $params;
    }
    
    /**
     * 检查参数是否为空，如果为空则返回默认值。
     * 
     * @param array $params 数据数组
     * @param string $paramKey 要检查的参数键
     * @param mixed $defaultValue 默认值
     * @return mixed 参数值或默认值
     */
    public function empty($params, $paramKey, $defaultValue)
    {
         return!empty($params[$paramKey])? $params[$paramKey] : $defaultValue;
    }
    
   
    /**
     * 处理时间字符串。
     * 
     * @param string $time 时间字符串
     * @param bool $type 是否返回相对时间，默认为 true
     * @return string 处理后的时间字符串
     */
    public function procetime(string $time, bool $type = true)
    {
        return $type ? time_ago($time) : date('Y-m-d H:i:s', $time);
    }
    
    /**
     * 处理布尔值。
     * 
     * @param mixed $params 输入值
     * @return bool 处理后的布尔值
     */
    public function procebool($params)
    {
       return !empty($params) ? true :false;
    }
    
    /**
     * 根据模型ID获取模型实例。
     * 
     * @param int $modelid 模型ID
     * @return object|bool 返回模型实例，如果模型ID无效则返回 false
     */
    public function procemodel(int $modelid)
    {
        return get_model($modelid);
    }
    
    /**
     * 处理用户图片路径。
     * 如果用户图片路径为空，则返回默认头像路径。
     * 
     * @param string $userpic 用户图片路径
     * @return string 返回处理后的用户图片路径
     */
    public function proceuserpic($userpic)
    {
        return !empty($userpic) ? $userpic : '/static/images/default-avatar.png';
    }
    
    /**
     * 获取用户昵称。
     * 
     * @param int $userid 用户ID
     * @return string 用户昵称
     */
    public function proceuserinfo(int $userid)
    {
        return get_userinfo($userid,'nickname');
    }
}