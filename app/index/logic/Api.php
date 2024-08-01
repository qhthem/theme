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
namespace app\index\logic;
use app\index\model\Parameter;
use app\index\model\ContentList;

class Api
{
    /**
     * 通用逻辑处理
     *
     * @param array  $data    数据
     * @param string $style   样式
     * @param int    $modelid 模型ID
     * @author zhaosong
     */
    public static function commonlogic(array $data, string $style, int $modelid)
    {
        if ($style == 1) {
            return ContentList::grid_qh($data, $modelid);
        } elseif ($style == 2) {
            return ContentList::grid_sidebar($data);
        } elseif ($style == 'waterfall') {
            return ContentList::grid_waterfall(self::waterfall_list());
        }
    }
    
    
    /**
     * 举例瀑布流数据列表
     *
     * @author zhaosong
     * @return array 返回瀑布流数据数组
     */    
    public static function waterfall_list()
    {
        $data = [
            ['id' => 1 ,'thumb'=>'/static/them/waterfall/Valentines-Day-20240513-10.jpg','url'=>'JavaScript:;','title'=>'干净的草地场景','catid'=>'瀑布流'],
            ['id' => 2 ,'thumb'=>'/static/them/waterfall/1a65714eb07c16_1_post.jpg','url'=>'JavaScript:;','title'=>'父爱如山','catid'=>'瀑布流'],
            ['id' => 3 ,'thumb'=>'/static/them/waterfall/1bb76b3547f6e9_1_post.jpg','url'=>'JavaScript:;','title'=>'6月快乐','catid'=>'瀑布流'],
            ['id' => 4 ,'thumb'=>'/static/them/waterfall/11db89b67c5f46_1_post.jpg','url'=>'JavaScript:;','title'=>'儿童节节日','catid'=>'瀑布流'],
            ['id' => 5 ,'thumb'=>'/static/them/waterfall/BOY-20240508-6.jpg','url'=>'JavaScript:;','title'=>'国风小男孩','catid'=>'瀑布流'],
            ['id' => 6 ,'thumb'=>'/static/them/waterfall/beauty-20240503-2.jpeg','url'=>'JavaScript:;','title'=>'人像ai','catid'=>'瀑布流'],
            
            ['id' => 7 ,'thumb'=>'/static/them/waterfall/51-20240505-3.jpeg','url'=>'JavaScript:;','title'=>'五一你去了哪儿','catid'=>'瀑布流'],
            ['id' => 8 ,'thumb'=>'/static/them/waterfall/Spring-Poster-20240426-01.jpg','url'=>'JavaScript:;','title'=>'春日海报','catid'=>'瀑布流'],
            ['id' => 9 ,'thumb'=>'/static/them/waterfall/1746b3260370ed_1_post.jpg','url'=>'JavaScript:;','title'=>'世界环境日','catid'=>'瀑布流'],
            ['id' => 10 ,'thumb'=>'/static/them/waterfall/1f9b67fc3c0747_1_post.jpg','url'=>'JavaScript:;','title'=>'世界海洋日','catid'=>'瀑布流'],
            
        ];
        
        return $data;
    }
    

    /**
     * 内容逻辑处理
     *
     * @param array $params 参数
     * @author zhaosong
     */
    public static function contentlogic(array $params)
    {
        $tablename = db(get_model($params['modelid']));
        $page = !empty($params['page']) ? $params['page'] : 1;
        $limit = !empty($params['limit']) ? $params['limit'] : 1;
        $field = !empty($params['field']) ? $params['field'] : '*';
        $order = !empty($params['order']) ? $params['order'] : 'listorder ASC,id DESC';
        $style = !empty($params['style']) ? $params['style'] : 1;

        $where = [];
        $where['status'] = 1;
        $where['catid'] = !empty($params['catid']) ? $params['catid'] : null;
        $where['thumb'] = !empty($params['thumb']) ? ['<>', ''] : null;
        $where['flag'] = !empty($params['flag']) ? ['LIKE', '%' . $params['flag'] . '%'] : null;
        $where['title'] = !empty($params['keyword']) ? ['LIKE', '%' . $params['keyword'] . '%'] : null;
        $where['userid'] = !empty($params['userid']) ? intval($params['userid']) : null;

        $res = $tablename->field($field)->where($where)->order($order)->paginate($page, $limit);

        $res['data'] = array_map(function ($item) {
            $item = Parameter::procecommon($item);
            return $item;
        }, $res['data']);

        $data['status'] = 200;
        $data['data'] = $res['total'] ? self::commonlogic($res['data'], $style, $params['modelid']) : ContentList::emptys();
        $data['total'] = $res['total'];
        return $data;
    }

    /**
     * 点赞或收藏逻辑处理
     *
     * @param array $params 参数
     * @author zhaosong
     */
    public static function like_favoritelogic(array $params)
    {
        if (empty($params['type'])) {
            return ['msg' => '类型错误', 'status' => 0];
        }

        if ($params['type'] == 'likes') {
            $status = get_cookie('_like' . $params['id']);
            $tablename = db(get_model($params['modelid']));
            if ($status) {
                $tablename->where(['id' => $params['id']])->setDec('likes');
                del_cookie('_like' . $params['id']);
                $count = $tablename->where(['id' => $params['id']])->value('likes');
                return ['msg' => '取消点赞', 'status' => 200, 'like' => $count, 'active' => 'd_l'];
            } else {
                $tablename->where(['id' => $params['id']])->setInc('likes');
                cookie('_like' . $params['id'], $params['id']);
                $count = $tablename->where(['id' => $params['id']])->value('likes');
                return ['msg' => '点赞成功', 'status' => 100, 'like' => $count, 'active' => 'a_l'];
            }
        }

        if ($params['type'] == 'favorite') {
            if (!get_cookie('_userid')) {
                return ['msg' => '请登录！', 'status' => 0];
            }

            $is = is_favorite($params['id'], $params['modelid'], $params['catid']);
            if ($is) {
                $f = is_favorite($params['id'], $params['modelid'], $params['catid'], true, false, true);
                return ['msg' => '成功取消收藏', 'status' => 200, 'favorite' => $f, 'active' => 'f_l'];
            } else {
                $f = is_favorite($params['id'], $params['modelid'], $params['catid'], false, true, true);
                return ['msg' => '成功收藏', 'status' => 100, 'favorite' => $f, 'active' => 'f_d'];
            }
        }
    }
}