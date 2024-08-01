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
namespace app\member\model;
use app\member\model\Finance;
use app\member\model\Experience as exps;

class Sign
{
    public $userid;

    /**
     * 构造函数
     * @author zhaosong
     */
    public function __construct()
    {
        $this->userid = intval(get_cookie('_userid'));
    }

    /**
     * 签到功能
     * @return array
     * @author zhaosong
     */
    public function index()
    {
        if (!$this->userid) return ['status' => 0, 'msg' => '请先登录！'];

        $res = db('member')->field('status,username')->where(['userid' => $this->userid])->find();
        if ($res['status'] != 1) return ['status' => 0, 'msg' => '你无权签到，请联系管理员！'];

        $data = db('sign')->field('inputtime,day')->where(['userid' => $this->userid])->order('id DESC')->find();
        $inputtime = $data ? $data['inputtime'] : 0;

        // 获取今天的0点的时间戳
        $starttime = strtotime(date('Y-m-d'));
        if ($inputtime > $starttime) return ['status' => 0, 'msg' => '今日已签到！'];

        // 连续签到天数
        $continuity_day = 1;
        if ($inputtime && (($inputtime + 24 * 3600) > $starttime)) {
            $continuity_day = $data['day'] + 1;
        }

        // 获取积分
        $point = $this->_get_point($continuity_day);

        $data = array(
            'userid' => $this->userid,
            'inputtime' => time(),
            'day' => $continuity_day,
            'point' => $point,
            'exp' => $point,
        );

        db('sign')->insert($data);
        // 奖励经验
        exps::_check_update_exp('sign');

        // 奖励积分
        Finance::add($point, $this->userid, $res['username'], '积分', '连续签到' . $continuity_day . '天', '');

        return ['status' => 200, 'msg' => '签到成功，积分 + ' . $point, 'data' => $data];
    }

    /**
     * 补签功能
     * @return array
     * @author zhaosong
     */
    public function repair_sign()
    {
        // 需要补签的日期，格式：2022-06-29
        $repair_sign = '2022-06-28';

        if (!$this->userid) return ['status' => 0, 'msg' => '请先登录！'];
        $res = db('member')->field('status,username')->where(['userid' => $this->userid])->find();
        if ($res['status'] != 1) return ['status' => 0, 'msg' => '你无权签到，请联系管理员！'];

        $repair_sign_time = strtotime($repair_sign . ' ' . date('H:i:s'));
        if (!$repair_sign_time || $repair_sign_time > time()) return ['status' => 0, 'msg' => '参数错误！'];

        if (time() - $repair_sign_time > 3600 * 24 * 30) return ['status' => 0, 'msg' => '只能补签30天之内的日期！'];

        // 查询比补签日期大的签到记录
        $repair_sign_time_t = strtotime($repair_sign);
        $data = db('sign')->field('id,inputtime,continuity_day,point')
            ->where(['userid' => $this->userid, 'inputtime' => ['>' => $repair_sign_time_t]])
            ->order('id ASC')->select();

        // 查询当天有没有签到
        $inputtime = $data ? $data[0]['inputtime'] : 0;
        if ($inputtime && $inputtime < ($repair_sign_time_t + 3600 * 24)) return ['status' => 0, 'msg' => '当天不需要补签！'];

        // 查询比补签当天小的记录，获取原来的连续签到次数
        $r = db('sign')->field('continuity_day,inputtime')
            ->where(['userid' => $this->userid, ['<' => $repair_sign_time_t]])
            ->order('id DESC')->find();
        $continuity_day = $r && $r['inputtime'] > ($repair_sign_time_t - 3600 * 24) ? intval($r['continuity_day']) + 1 : 1;

        // 获取积分
        $point = $this->_get_point($continuity_day);

        // 插入补签当天记录
        db('sign')->insert(array(
            'userid' => $this->userid,
            'inputtime' => $repair_sign_time,
            'day' => $continuity_day,
            'point' => $point,
            'exp' => $point,
        ));

        // 奖励补签当天积分
        Finance::add($point, $this->userid, $res['username'], '通过补签，得到连续签到' . $continuity_day . '天', '');

        // 弥补上补签之后的记录
        foreach ($data as $key => $val) {
            db('sign')->delete(['id' => $val['id']]);
            db('sign')->insert(array(
                'userid' => $this->userid,
                'inputtime' => $val['inputtime'],
                'day' => $continuity_day + $key + 1,
                'point' => $val['point'],
                'exp' => $val['point'],
            ));
        }

        return ['status' => 200, 'msg' => '恭喜你补签成功'];
    }

    /**
     * 获取积分方法
     * @param $continuity_day
     * @return int
     * @author zhaosong
     */
    private function _get_point($continuity_day)
    {
        // 如果连续签到次数小于5天，则是第一天2分，第二天4分...
        if ($continuity_day < 5) {
            return $continuity_day * 2;
        }

        // 如果连续签到次数大于等于5天，则为固定值
        return 10;
    }
    
    
    /**
     * 根据用户id获取签到信息
     *
     * @param $userid
     * @return array
     */
    public function _get_sign_info()
    {
        
        $userid = intval($this->userid);
        if(!$userid) return ['sign'=>'签到'];
        
    	$data = db('sign')->field('inputtime,day')->where(['userid' => $userid])->order('id DESC')->find();
    	$inputtime = $data ? $data['inputtime'] : 0;
    
    	$starttime = strtotime(date('Y-m-d'));
    	if($inputtime > $starttime)  return ['sign'=>'已签到'];
    
    	$continuity_day = 0;
    	if($inputtime && (($inputtime + 24*3600)>$starttime)){ 
    		$continuity_day = $data['day'];
    	}
    
    	return ['sign'=>'签到'];
        
    }
    
    
}