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
namespace extend\taglib;

/**
 * Tag 类
 *
 * @author zhaosong
 */
class Tag
{
    public $pages;

    /**
     * 导航标签
     *
     * @param array $data
     * @return array
     */
    public function tagNav($data): array
    {
        $where = isset($data['where']) ? $data['where'] : 'status=1';
        $field = isset($data['field']) ? $data['field'] : '*';
        $order = isset($data['order']) ? $data['order'] : 'listorder ASC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        return db('category')->field($field)->where(convertStringToWhereArray($where))->order($order)->limit($limit)->select();
    }

    /**
     * 列表标签
     *
     * @param array $data
     * @return array
     */
    public function tagLists($data): array
    {
        $where = [];
        $where['status'] = 1;
        $modelid = isset($data['modelid']) ? $data['modelid'] : null;
        $where['catid'] = isset($data['catid']) ? $data['catid'] : null;
        $where['id'] = isset($data['id']) ? intval($data['id']) : null;
        $field = isset($data['field']) ? $data['field'] : '*';
        $order = isset($data['order']) ? $data['order'] : 'listorder ASC,id DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        $where['thumb'] = isset($data['thumb']) ? ['<>', ''] : null;
        $where['flag'] = isset($data['flag']) ? ['LIKE', '%' . $data['flag'] . '%'] : null;
        $page = Request()::get('page', 1);

        $tablename = db(get_model($modelid));

        if (isset($data['page'])) {
            $res = $tablename->field($field)->where($where)->order($order)->paginate($page, $limit);
            $this->pages = $tablename->render($res);
            $res = $res['data'];
        } else {
            $res = $tablename->field($field)->where($where)->order($order)->limit($limit)->select();
        }

        return $res;
    }

    /**
     * 全部标签
     *
     * @param array $data
     * @return array
     */
    public function tagAll($data): array
    {
        $where = isset($data['where']) ? $where : 'status = 1';
        $field = isset($data['field']) ? $data['field'] : 'modelid,catid,id,userid,username,title,inputtime,updatetime,url,thumb';
        $order = isset($data['order']) ? $data['order'] : 'allid DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';

        return db('content')->field($field)->where(convertStringToWhereArray($where))->order($order)->limit($limit)->select();
    }

    /**
     * 标签标签
     *
     * @param array $data
     * @return array
     */
    public function tagTag($data): array
    {
        $where = [];
        $field = isset($data['field']) ? $data['field'] : '';
        $where['catid'] = isset($data['catid']) ? $data['catid'] : null;
        $order = isset($data['order']) ? $data['order'] : 'id DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        return db('tag')->field($field)->where($where)->order($order)->limit($limit)->select();
    }

    /**
     * 内容标签标签
     *
     * @param array $data
     * @return array
     */
    public function tagCentent_tag($data): array
    {
        $where = [];
        $where['modelid'] = isset($data['modelid']) ? intval($data['modelid']) : 1;
        $where['aid'] = isset($data['id']) ? intval($data['id']) : 0;
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        return db('tag')->field('id,tag')->where($where)->limit($limit)->select();
    }

    /**
     * 关联标签
     *
     * @param array $data
     * @return array|bool
     */
    public function tagRelation($data)
    {
        $modelid = isset($data['modelid']) ? intval($data['modelid']) : 1;
        $relation = isset($data['relation']) ? intval($data['relation']) : $modelid;
        $id = isset($data['id']) ? intval($data['id']) : 0;
        $field = isset($data['field']) ? $data['field'] : '*';
        $limit = isset($data['limit']) ? $data['limit'] : '20';

        $where = [];
        $where['modelid'] = $modelid;
        $where['aid'] = $id;

        $limit = $modelid == $relation ? $limit + 1 : $limit;
        $tag = db('tag')->field('tag')->where($where)->select();
        $tags = [];
        foreach ($tag as $val) {
            $tags[] = $val['tag'];
        }

        $res = db('tag')->field('aid')->where(['tag' => ['in' => $tags]])->limit($limit)->order('aid DESC')->select();
        $ids = [];
        foreach ($res as $val) {
            if ($modelid == $relation && $val['aid'] == $id) continue;
            $ids[] = $val['aid'];
        }

        if (empty($ids)) return false;

        if ($modelid != $relation) return false;
        $wheres['id'] = ['in' => $ids];
        $wheres['status'] = 1;

        $tablename = get_model($modelid);
        $tabledata = db($tablename)->field($field)->where($wheres)->order('id DESC')->limit($limit)->select();

        return $tabledata;
    }

    /**
     * 链接标签
     *
     * @param array $data
     * @return array
     */
    public function tagLink($data): array
    {
        $field = isset($data['field']) ? $data['field'] : '*';
        $where = isset($data['where']) ? $data['where'] : 'status = 1';
        $order = isset($data['order']) ? $data['order'] : 'listorder ASC, id DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        return db('link')->field($field)->where(convertStringToWhereArray($where))->order($order)->limit($limit)->select();
    }

    /**
     * 广告标签
     *
     * @param array $data
     * @return array
     */
    public function tagBanner($data): array
    {
        $field = isset($data['field']) ? $data['field'] : '*';
        $order = 'listorder ASC,id DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        $typeid = isset($data['typeid']) ? intval($data['typeid']) : 0;
        $where = $typeid ? 'status = 1 , typeid=' . $typeid : 'status = 1';
        return db('banan')->field($field)->where(convertStringToWhereArray($where))->order($order)->limit($limit)->select();
    }

    /**
     * 点击标签
     *
     * @param array $data
     * @return array
     */
    public function tagHits($data): array
    {
        $modelid = isset($data['modelid']) ? intval($data['modelid']) : 1;
        $catid = isset($data['catid']) ? intval($data['catid']) : '';
        $field = isset($data['field']) ? $data['field'] : '*';
        $limit = isset($data['limit']) ? $data['limit'] : '20';

        $where = [];
        $where['status'] = 1;
        if ($catid) {
            $where['catid'] = ['in' => [$catid]];
        }

        $where['thumb'] = isset($data['thumb']) ? ['<>', ''] : null;
        $day = isset($data['day']) ? $data['day'] : '';
        if ($day) {
            $where['updatetime'] = ['>', (time() - intval($data['day']) * 86400)];
        }
        $tablename = db(get_model($modelid));

        return $tablename->field($field)->where($where)->order('`click` DESC')->limit($limit)->select();
    }

    /**
     * 相邻标签
     *
     * @param array $data
     * @return array
     */
    public function tagAdjacent($data): array
    {
        $id = isset($data['id']) ? intval($data['id']) : '';
        $modelid = isset($data['modelid']) ? intval($data['modelid']) : 1;
        $catid = isset($data['catid']) ? intval($data['catid']) : '';
        $field = isset($data['field']) ? $data['field'] : '*';
        $tablename = db(get_model($modelid));

        // 查询上一篇文章
        $pre = $tablename->field($field)->where([
            'id' => ['<', $id],
            'catid' => $catid
        ])->limit(1)->order('id DESC')->select();

        if (empty($pre)) {
            $pre = [
                'title' => '没有上一篇了',
                'url' => '#'
            ];

            $pre = [$pre];
        }

        // 查询下一篇文章
        $next = $tablename->field($field)->where([
            'id' => ['>', $id],
            'catid' => $catid
        ])->limit(1)->order('id ASC')->select();

        if (empty($next)) {
            $next = [
                'title' => '没有下一篇了',
                'url' => '#'
            ];

            $next = [$next];
        }

        // 返回上一篇和下一篇文章的二维数组
        return [
            'pre' => $pre,
            'next' => $next
        ];
    }
    
    /**
     * 根据提供的数据参数构建并执行一个标签表查询，然后返回查询结果数组。
     * 
     * @param array $data 包含查询参数的关联数组，有以下键值对：
     *                      - 'value' (string): 表名，默认为空字符串。
     *                      - 'field' (string): 要查询的字段，默认为 '*'。
     *                      - 'order' (string): 排序规则，默认为 'id DESC'。
     *                      - 'limit' (string): 查询结果的限制数量，默认为 '20'。
     *                      - 'where' (string): 查询条件，默认为空字符串。
     * @return array 返回一个包含查询结果的数组。
     */
    public function tagTable($data): array
    {
        $table = isset($data['value']) ? $data['value'] : '';
        $field = isset($data['field']) ? $data['field'] : '*';
        $order = isset($data['order']) ? $data['order'] : 'id DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        $where = isset($data['where']) ? $data['where'] : '';
        
        return db($table)->field($field)->where(convertStringToWhereArray($where))->order($order)->limit($limit)->select();
    }    
    
}