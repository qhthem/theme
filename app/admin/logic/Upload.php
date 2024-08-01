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

class Upload
{
    /**
     * 列出逻辑
     *
     * @param array $params 参数
     * @return array 返回数据
     */
    public static function listlogic(array $params)
    {
        $limit  = !empty($params['limit']) ? $params['limit'] : 7;
        $page = !empty($params['page']) ? $params['page'] : 1;
        $time = !empty($params['time']) ? $params['time'] : null;
        
        $where = [];
        $where['userid'] = !empty(get_cookie('_userid')) ? get_cookie('_userid'):null;
        if(!empty($params['time'])){
            $where['uploadtime'] = ['between', [$time[0], $time[1]]];
        }
        
        $where['ext'] = !empty($params['typeimg']) ? $params['typeimg'] : null;
        $field = 'id,originname,filename,app,uploadtime,filesize,uploadip,username,arrtype,ext';
        $res = db('upload')->where($where)->order('id DESC')->field($field)->paginate($page, $limit);
        
        $res['data'] = array_map(function($item) {
            $item['uploadtime'] = date("Y-m-d H:i:s",$item['uploadtime']);
            $item['filesize'] = Handle::formatBytes($item['filesize']);
            $item['originname'] = $item['arrtype'] == 'down' ? file_icon($item['ext']): $item['originname'];
            return $item;
        }, $res['data']);
        
        $data['status'] = 200;
        $data['data'] = $res;
        $data['typeimg'] = ['png','jpg','jpeg','gif','zip','rar','mp3','mp4'];
        return $data;
    }
    
    /**
     * 上传逻辑
     *
     * @param array $params 参数
     * @param mixed $file 文件
     * @return array 返回数据
     */
    public static function uploadlogic($file,$params= '' )
    {
        if(empty($file)){
            return ['status'=>0,'msg'=>'没有获取到文件！'];
        }
        
        $upload = Uploads()->upload($file);
            $data = [
                'app'=> empty($params) ? "admin" :$params,
                'userid'=> Session()::get('admininfo')['adminid'],
                'username'=> Session()::get('admininfo')['adminname'],
                'uploadip'=> getip()
            ];
            $uploaddata = array_merge($upload, $data);
            $r = db('upload')->insert($uploaddata);
            
            if(!empty(C('images_type'))){
                $filepath = C('upload_path').$uploaddata['filename'].'.'.$uploaddata['ext'];
                return self::upload_wanglogic($filepath);
            }
            else{
                return ['status'=>$r ? 200:0,'msg'=>$r ? '上传成功！':'上传失败！','errorCode'=>0,'data'=>['alt'=>$uploaddata['filename'],'src'=>$upload['originname']]];
            }        
    }
    
    /**
     * 上传逻辑 - 图片处理
     *
     * @param string $filepath 文件路径
     * @return array 返回数据
     */
    public static function upload_wanglogic($filepath)
    {
        
        if(C('images_type') == 'watermark'){
            if(!is_file(C('images_watermark'))){
                return ['status'=>0,'msg'=>'水印图不存在！'];
            }
            Images($filepath)->water(C('images_watermark'),C('images_pos'),100)->save($filepath);;
        }
        else{
            Images($filepath)
            ->text(C('images_content'), C('images_font'), C('images_font_size'), C('images_font_color'))
            ->save($filepath);
        }
        
        return ['status'=>200,'msg'=>'上传成功并处理图片完成！'];
    }
    
    /**
     * 删除逻辑
     *
     * @param mixed $id 文件ID
     * @return array 返回数据
     */
    public static function deletelogic($id)
    {
        if (!$id) return ['status' => 0, 'msg' => '参数错误'];
        foreach(explode(',',$id) as $v){
            $del = db('upload')->where(['id'=>$v])->value('originname');
            self::filename_dellogic($del);
            db('upload')->where(['id' => $v])->delete();
        }
        
        return ['status' => 200, 'msg' => '操作成功'];
    } 
    
    /**
     * 文本更新逻辑
     *
     * @param array $params 参数
     * @return array 返回数据
     */
    public static function textuplogic(array $params)
    {
        if (empty($params)) return ['status' => 0, 'msg' => '参数错误'];
        $r = db('upload')->where(['id'=>$params['id']])->update(['filename'=>$params['content']]);
        return ['status' => 200, 'msg' => '操作成功'];
    }   
    
    /**
     * 文件名删除逻辑
     *
     * @param string $filename 文件名
     * @return bool 返回结果
     */
    public static function filename_dellogic($filename)
    {
        $filename = app()::getRunPath(). $filename;
        if(file_exists($filename)) {
            unlink($filename);
            return true;
        } else {
            return false;
        }
    }
}