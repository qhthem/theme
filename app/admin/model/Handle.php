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
use qhphp\app\Model;

class Handle extends Model
{
    /**
     * 生成列表树
     *
     * @param array $data 列表数据
     * @param int $pid 父级 ID
     * @param array $config 配置数组，包含两个元素：第一个元素表示 ID 字段名，第二个元素表示父级 ID 字段名
     * @return array 生成的树形结构
     */
     public static function _generateListTree($data, $pid = 0, $config = [])
     {
        return Model::_generateListTree($data, $pid, $config);
    }
   
    /**
     * 生成树形结构的分类数组。
     *
     * @param array  $cate      分类数组，包含所有分类信息的数组
     * @param string $name      分类名称的键名
     * @param string $lefthtml  分类前缀，用于在分类名称前添加缩进
     * @param int    $pid       父分类 ID，用于查询子分类
     * @param int    $lvl       分类层级，用于设置缩进级别
     * @return array            生成的树形结构数组
     */
    public static function tree_cate($cate, $name, $id,$lefthtml = '|— ', $pid = 0, $lvl = 0)
    {
        return Model::tree_cate($cate, $name, $id,$lefthtml, $pid, $lvl);
    }
    
    
    /**
     * 删除目标目录及其所有文件和子目录
     *
     * @param string $path 要删除的目录路径
     * @param bool $delDir 是否删除目录本身（true 删除，false 不删除）
     * @return bool 删除成功返回 true，失败返回 false
     */
    public static function del_target_dir($path, $delDir)
    {
        return Model::del_target_dir($path, $delDir);
    } 
    
    /**
     * 将字节数转换为带单位的字符串。
     *
     * @param int $bytes 字节数
     * @param int $precision 小数点后保留的位数，默认为 2 位
     * @return string 转换后的字符串，例如：1.50 MB
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        return Model::formatBytes($bytes, $precision);
    }
    
    /**
     * 根据文件扩展名判断文件类型。
     *
     * @param string $extension 文件扩展名。
     *
     * @return string 文件类型，可以是 "image"、"video" 或 "down"。
     */
    public static function extensions($extension)
    {
        return Model::extensions($extension);
    }
    
    /**
     * 递归移除多维数组中的空数组元素。
     *
     * @param array $array 要处理的多维数组
     */
    public static function removeEmptyArrays(&$array)
    {
        return Model::removeEmptyArrays($array);
    }
    
    /**
     * 去除字符串中的反斜杠
     *
     * @param mixed $string 需要处理的字符串或数组
     * @return mixed 处理后的字符串或数组
     */
    public static function new_stripslashes($string) {
        return Model::new_stripslashes($string);
    }
    
    /**
     * 将数组转换为字符串
     *
     * @param array $data 需要转换的数组
     * @param int $isformdata 是否处理表单数据，默认为 1
     * @return string 转换后的字符串
     */
    public static function array2string($data, $isformdata = 1) {
        return Model::array2string($data, $isformdata);
    }
    
    /**
     * 将字符串转换为数组
     *
     * @param string $data 要转换的字符串
     * @return array 转换后的数组
     * @author zhaosong
     */
    public static function string2array($data) {
        return Model::string2array($data);
    }
    
    /**
     * 截取字符串
     *
     * @param string $string 要截取的字符串
     * @param int $length 指定的截取长度
     * @return string 截取后的字符串
     * @author zhaosong
     */    
    public static function truncateString($string, $length)
    {
        return Model::truncateString($string, $length);
    }    
    
}