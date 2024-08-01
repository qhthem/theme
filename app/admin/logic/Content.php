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
use app\admin\model\Content as Contentmodel;
use app\admin\logic\Form;

class Content
{
    /**
     * 作者: zhaosong
     * 功能: 获取内容列表
     * @param array $params 参数数组
     * @return array 返回数据数组
     */
    public static function indexlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 10;
        $page = !empty($params['page']) ? $params['page'] : 1;
        
        $where = [];
        $time = !empty($params['time']) ? $params['time'] : null;
        if(!empty($params['time'])){
            $where['inputtime'] = ['between', [$time[0], $time[1]]];
        }
        
        $where['modelid'] = !empty($params['modelid']) ? $params['modelid'] : Contentmodel::getdefaultmodel(0,false);
        
        $where['title'] = !empty($params['title']) ? ['like', '%'.$params['title'].'%'] : null;
        $where['catid'] = !empty($params['catid']) ? $params['catid'] : null;
        
        $field = 'allid,id,modelid,title,catid,username,status,updatetime,inputtime';
        $res = db('content')->field($field)->where($where)->order('id DESC')->paginate($page, $limit);
        
        $res['data'] = array_map(function ($item) {
            $item['is_push'] = self::getcontentlogic(['id'=>$item['id']],$item['modelid'], 'is_push');
            $item['title'] = str_cut($item['title'],150);
            $item['catid'] = self::categorylogic(['catid'=>$item['catid']], 'catname');
            $item['status'] = $item['status'] ? true : false;
            $item['click'] = self::getcontentlogic(['id'=>$item['id']],$item['modelid'], 'click');
            $item['updatetime'] = $item['updatetime'] ? date("Y-m-d H:i:s", $item['updatetime']):date("Y-m-d H:i:s", $item['inputtime']);
            return $item;
        }, $res['data']);
    
        $data['status'] = 200;
        $data['modelid'] = self::modeselectlogic();
        $data['data'] = $res;
        
        return $data;
    }
    
    /**
     * 作者: zhaosong
     * 功能: 添加内容
     * @param array $params 参数数组
     * @return array 返回数据数组
     */
    public static function addlogic(array $params)
    {
    
        if(empty($params['catid'])){
            return (['status'=>00,'msg'=>'栏目不能为空']);
        }
        
        // 处理内容
        $params['content'] = self::content_img_attributes($params['content'],$params['title']);
        // $params['status'] = 1;
        
        $params['modelid'] = !empty($params['modelid'])
        ? Contentmodel::getdefaultmodel((int) $params['modelid'],false)
        : Contentmodel::getdefaultmodel((int)0, false);
        $content_tabname = Contentmodel::getdefaultmodel($params['modelid']);
        
        if(isset($params['auto_thum']) && empty($params['thumb'])){
            Contentmodel::get_first_image_url($params['content']);
        }
        
        $form =  Form::FormValidation($params,$params['modelid']);
        if($form){
            return $form;
        }
        
        foreach($params as $_k=>$_v) {
            $params[$_k] = !is_array($params[$_k]) ? $_v : Contentmodel::content_disposemodel($_v);
        }
        
        $_p = Contentmodel::arrayprocessing($params);
    
        $id = db($content_tabname)->insertGetId(array_merge($params,$_p));
        
        $url = self::categorylogic(['catid'=>$params['catid']], 'pclink').$id.'.html';
        db($content_tabname)->where(['id' => $id])->update(['url' => $url]);
        
        if(!empty($params['tags'])){
            Contentmodel::tag_dispose($params['catid'], explode(',',$params['tags']), $id,$params['modelid']);
        }
        
        $params['id'] = $id;
        $params['url'] = $url;
        db('content')->insertGetId(array_merge($params,$_p));
        
        return ['status' => 200, 'msg' => '添加成功'];
        
    }
    
    /**
     * 作者: zhaosong
     * 功能: 更新内容状态
     * @param array $params 参数
     * @return array 返回操作结果
     */
    public static function statuslogic(array $params)
    {
        if (empty($params['id'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
        $where = ['id' => $params['id']];
    
        db(self::getcontentmodellogic($params['id']))->where($where)->update($params);
        db('content')->where($where)->update($params);
    
        return ['status' => 200, 'msg' => '操作成功'];
    }
    
    /**
     * 作者: zhaosong
     * 功能: 获取更新内容信息
     * @param array $params 参数
     * @return array 返回操作结果
     */
    public static function getUpdateInfoslogic(array $params)
    {
        if (empty($params['data'])) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
    
        $data = $params['data'];
    
        $res = db(Contentmodel::getdefaultmodel($data['modelid']))->where(['id' => $data['id']])->find();
        $tag = db('tag')->where(['aid' => $data['id'], 'modelid' => $data['modelid']])->field('tag')->select();
    
        $v_tag = [];
        foreach ($tag as $v) {
            $v_tag[] = $v['tag'];
        }
    
        foreach ($res as $key => &$value) {
            $res[$key] = Form::form_updata($key, $value);
        }
    
        $res['tags'] = $v_tag;
        $res['flag'] = explode(',', $res['flag']);
        $res['inputtime'] = (int) $res['inputtime'] * 1000;
        return ['status' => 200, 'data' => $res];
    }    
    
    /**
     * 作者: zhaosong
     * 功能: 获取字段列表
     * @return array 返回操作结果
     */
    public static function getFieldListlogic()
    {
        $list = db('category')->where(['type'=>1])->field('catname,catid,parentid')->order('listorder DESC')->select();
        $lists = Handle::_generateListTree($list, 0, ['catid', 'parentid']);
        $data['data'] = $lists;
        return (['status' => 200, 'data' => $data]);
    }
    
    /**
     * 作者: zhaosong
     * 功能: 获取分类信息
     * @param array $param 参数
     * @param string $field 字段
     * @return mixed 返回操作结果
     */
    public static function categorylogic($param, $field)
    {
        return db('category')->where($param)->value($field);
    }
    
    /**
     * 作者: zhaosong
     * 功能: 获取模型选择列表
     * @return array 返回操作结果
     */
    public static function modeselectlogic()
    {
        $model = db('model')->field('modelid,name')->order('modelid DESC')->select();
        foreach ($model as $k => $res) {
            $model[$k]['label'] = $model[$k]['name'];
            $model[$k]['value'] = $model[$k]['modelid'];
            unset($model[$k]['name'], $model[$k]['modelid']);
        }
        return ['status' => 200, 'data' => $model, 'catid' => self::getFieldListlogic()];
    }
    
    /**
     * 作者: zhaosong
     * 功能: 更新内容逻辑
     * @param array $params 参数
     * @return array 返回操作结果
     */
    public static function updatelogic(array $params)
    {
        if (empty($params['catid'])) {
            return ['status' => 00, 'msg' => '栏目不能为空'];
        }
        
        $params['content'] = self::content_img_attributes($params['content'],$params['title']);
        
        $where = ['id' => $params['id']];
        $modelid = db('content')->where($where)->value('modelid');
    
        if (isset($params['auto_thum']) && empty($params['thumb'])) {
            Contentmodel::get_first_image_url($params['content']);
        }
    
        $form = Form::FormValidation($params, $modelid);
        if ($form) {
            return $form;
        }
    
        foreach ($params as $_k => $_v) {
            $params[$_k] = !is_array($params[$_k]) ? $_v : Contentmodel::content_disposemodel($_v);
        }
    
        $_p = Contentmodel::arrayprocessing($params);
    
        if (!empty($params['tags'])) {
            Contentmodel::tag_dispose($params['catid'], explode(',', $params['tags']), $params['id'], $modelid);
        }
    
        $content_tabname = db(self::getcontentmodellogic($params['id']));
    
        $content_tabname->where($where)->updateFilter(array_merge($params, $_p));
        db('content')->where($where)->updateFilter(array_merge($params, $_p));
    
        return ['status' => 200, 'msg' => '更新成功'];
    }
    
    /**
     * 作者: zhaosong
     * 功能: 删除内容逻辑
     * @param string $id 内容ID
     * @return array 返回操作结果
     */
    public static function deletelogic($id)
    {
        if (empty($id)) return ['status' => 0, 'msg' => '参数错误'];
        foreach (explode(',', $id) as $v) {
            db(self::getcontentmodellogic($v))->where(['id' => $v])->delete();
            db('content')->where(['id' => $v])->delete();
            db('tag')->where(['aid' => $v])->delete();
        }
        return ['status' => 200, 'msg' => '操作成功'];
    } 
    
    /**
     * 作者: zhaosong
     * 功能: 批量操作内容逻辑
     * @param array $params 参数
     * @return array 返回操作结果
     */
    public static function batchlogic(array $params)
    {
        if (empty($params['ids'])) return ['status' => 0, 'msg' => '参数错误,请重新选择！'];
        $batch = $params['batch'];
    
        if ($batch['value'] == 1) {
            Contentmodel::movemodel($params['ids'], $batch['catid']);
            return ['status' => 200, 'msg' => '操作成功'];
        } elseif ($batch['value'] == 2) {
            Contentmodel::copymodel($params['ids'], $batch['catid']);
            return ['status' => 200, 'msg' => '操作成功'];
        } elseif ($batch['value'] == 3) {
            $success = Contentmodel::baiduPushmodel($params['ids'],$params['modelid']);
            if (!empty($success['status'])) {
                return $success;
            }
        } elseif ($batch['value'] == 4) {
            Contentmodel::statusmodel($params['ids'], $batch['status']);
            return ['status' => 200, 'msg' => '操作成功'];
        }
    }
    
    /**
     * 作者: zhaosong
     * 功能: 获取内容逻辑
     * @param array $param 参数
     * @param int $modelid 模型ID
     * @param string $field 字段
     * @return mixed 返回查询结果
     */
    public static function getcontentlogic($param, $modelid, $field)
    {
        return db(Contentmodel::getdefaultmodel($modelid))->where($param)->value($field);
    }
    
    /**
     * 作者: zhaosong
     * 功能: 获取内容模型逻辑
     * @param int $id 内容ID
     * @return string 返回内容模型表名
     */
    public static function getcontentmodellogic($id)
    {
        if (empty($id)) {
            return ['status' => 00, 'msg' => '参数错误'];
        }
        $where = ['id' => $id];
    
        $modelid = db('content')->where($where)->value('modelid');
        $content_tabname = Contentmodel::getdefaultmodel($modelid);
    
        return $content_tabname;
    }
    
    /**
     * 处理内容中的 img 标签属性
     *
     * @param string $content 包含 img 标签的 HTML 内容
     * @param string $title 新的 title 和 alt 属性值，默认为空字符串
     * @return string 处理后的 HTML 内容，已更新 img 标签的属性
     * @author zhaosong
     */
    public static function content_img_attributes(string $content, string $title = ''): string {
        $content = Contentmodel::addClassesToPreTag($content);
        $content = Contentmodel::remove_img_attributes($content);
        $content = Contentmodel::updateImgAttributes($content, $title);
        return $content;
    }

}