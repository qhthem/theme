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
namespace app\admin\logic;
use app\admin\model\Handle;
use Transliterator;

class Category
{
     /**
     * 获取栏目列表。
     *
     * @return array 栏目列表。
     * @author zhaosong
     */
    public static function indexlogic()
    {
        $field = 'catid,parentid,modelid,type,status,publish,catname';
        $res = db('category')->field($field)->order('listorder DESC')->select();
        foreach ($res as $k => $v) {
            $res[$k]['modelid'] = self::modellogic(['modelid' => $res[$k]['modelid']], 'name');
            $res[$k]['status'] = $res[$k]['status'] ? true : false;
        }
        $res['data'] = Handle::_generateListTree($res, 0, ['catid', 'parentid']);
        $data['status'] = 200;
        $data['data'] = $res['data'];
        return $data;
    }
    
    /**
     * 添加栏目。
     *
     * @param array $params 栏目信息。
     * @return array 操作结果。
     * @author zhaosong
     */
    public static function addlogic(array $params)
    {
        if (empty($params['catname'])) {
            return ['status' => 0, 'msg' => '栏目名称不能为空'];
        }
    
        $params['modelid'] = $params['modelid'] ?? self::modellogic(['isdefault' => 1], 'modelid');
        $params['catdir'] = $params['catdir'] ?? self::transliterators($params['catname']);
        $params['type'] = $params['type'] ?? 1;
    
        $template = self::modellogic(['modelid' => $params['modelid']], 'tablename');
    
        $params['category_template'] = $params['type'] == 2 ? 'category_' . $template : '';
        $params['list_template'] = $params['type'] == 1 ? 'list_' . $template : '';
        $params['show_template'] = $params['type'] == 1 ? 'show_' . $template : '';
    
        $catid = db('category')->insertGetId($params);
    
        if ($params['type'] == 1 || $params['type'] == 2) {
            $pclink = ['pclink' => '/' . $params['catdir'] . '/'];
            db('category')->where(['catid' => $catid])->update($pclink);
        }
        
        self::Repairsection(empty($params['parentid']) ? 0 :$params['parentid']);
    
        return ['status' => 200, 'msg' => '栏目添加成功'];
    }
    
    /**
     * 获取栏目列表。
     *
     * @return array 栏目列表。
     * @author zhaosong
     */
    public static function getFieldListlogic()
    {
        $list = db('category')->field('catname,catid,parentid')->order('listorder DESC')->select();
        $lists = Handle::_generateListTree($list, 0, ['catid', 'parentid']);
    
        $model = db('model')->field('modelid,name')->order('modelid DESC')->select();
        foreach ($model as $k => $res) {
            $model[$k]['label'] = $model[$k]['name'];
            $model[$k]['value'] = $model[$k]['modelid'];
            unset($model[$k]['name'], $model[$k]['modelid']);
        }
        return (['status' => 200, 'data' => $lists, 'model' => $model]);
    }
    
    /**
     * 获取更新栏目信息。
     *
     * @param int $catid 栏目ID。
     * @return array 栏目信息。
     * @author zhaosong
     */
    public static function getUpdateInfoslogic($catid)
    {
        if (!$catid) return ['status' => 0, 'msg' => '参数错误'];
        $res = db('category')->where(['catid' => $catid])->find();
        return ['status' => 200, 'data' => $res];
    }
    
    /**
     * 更新栏目。
     *
     * @param array $params 包含栏目信息的数组。
     * @return array 操作结果。
     * @author zhaosong
     */
    public static function updatelogic(array $params)
    {
        if (empty($params['catname'])) {
            return ['status' => 0, 'msg' => '栏目名称不能为空'];
        }
    
        $params['modelid'] = $params['modelid'] ?? self::modellogic(['isdefault' => 1], 'modelid');
        $params['catdir'] = !empty($params['catdir']) ? $params['catdir'] : self::transliterators($params['catname']);
        $params['type'] = $params['type'] ?? 1;
    
        $template = self::modellogic(['modelid' => $params['modelid']], 'tablename');
    
        $params['category_template'] = $params['type'] == 2 ? 'category_' . $template : '';
        $params['list_template'] = $params['type'] == 1 ? 'list_' . $template : '';
        $params['show_template'] = $params['type'] == 1 ? 'show_' . $template : '';
    
        if ($params['type'] == 1 || $params['type'] == 2) {
            $params['pclink'] = '/' . $params['catdir'] . '/';
        }
    
        db('category')->where(['catid' => $params['catid']])->update($params);
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 更新分类状态。
     *
     * @param array $params 包含分类ID和状态的数组。
     * @return array 操作结果。
     * @author zhaosong
     */
    public static function statuslogic(array $params)
    {
        if (empty($params['catid'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
    
        db('category')->where(['catid' => $params['catid']])->update($params);
        return ['status' => 200, 'msg' => '操作成功'];
    }
    
    /**
     * 删除分类。
     *
     * @param string $catid 要删除的分类ID，多个ID用逗号分隔。
     * @return array 操作结果。
     * @author zhaosong
     */
    public static function deletelogic($catid)
    {
        $category = db('category');
        if (empty($catid)) return (['status' => 0, 'msg' => '参数错误']);
        
        foreach (explode(',',$catid) as $catids){
            $parentid = $category->where(['catid' => $catid])->field('parentid,arrchildid')->find();
            if(!empty($parentid['arrchildid'])){
                return (['status' => 0, 'msg' => '请先删除该分类下的子分类！']);
            }
            $category->where(['catid' => $catid])->delete(); 
            self::Repairsection($parentid['parentid']);
        }
        
        return (['status' => 200, 'msg' => '操作成功']);
    }
    
    /**
     * 获取模型信息。
     *
     * @param array $param 查询参数。
     * @param string $field 要获取的字段。
     * @return mixed 查询结果。
     * @author zhaosong
     */
    public static function modellogic($param, $field)
    {
        return get_model_where($param, $field);
    }
    
    /**
     * 检查字符串是否包含中文字符。
     *
     * @param string $string 要检查的字符串。
     * @return boolean 是否包含中文字符。
     * @author zhaosong
     */
    public static function containsChinese($string)
    {
        return preg_match('/[\x{4e00}-\x{9fa5}]/u', $string);
    }
    
    /**
     * 将输入的文本进行转换，并删除空格。
     *
     * @param string $text 输入的文本。
     * @return string 转换后的文本，空格已删除。
     * @author zhaosong
     */
    public static function transliterators($text) {
        $transliterator = Transliterator::create('Any-Latin; Latin-ASCII');
        $str = transliterator_transliterate($transliterator, $text);
        $result = str_replace(' ', '', $str);
        return $result;
    }
    
    /**
     * 修复分类节点下的子节点ID列表
     * @param int $parentId 分类节点的ID
     * @author zhaosong
     */
    private static function Repairsection($parentId) {
        // 更新指定分类节点的子节点ID列表
        db('category')->where(['catid' => $parentId])->update(['arrchildid' => self::getChildrenIds($parentId)]);
    }
    
    /**
     * 获取指定分类节点下的所有子节点ID
     * @param int $parentId 分类节点的ID
     * @param array $result 存储子节点ID的数组，默认初始化为空数组
     * @return string 以逗号分隔的子节点ID字符串
     * @author zhaosong
     */
    private static function getChildrenIds($parentId, &$result = []) {
        // 查询指定分类节点的子节点
        $children = db('category')->field('catid')
            ->where(['parentid' => $parentId, 'status' => 1])->select();
    
        // 遍历子节点并将ID添加到结果数组中
        foreach ($children as $child) {
            $result[] = $child['catid'];
        }
    
        // 返回去重后的子节点ID字符串
        return implode(',', array_unique($result));
    } 
}
