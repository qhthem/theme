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
use app\member\logic\Common;

class Experience
{
    
    /**
     * 根据类型更新用户经验值和积分
     *
     * @param string $type 更新类型（article, comment, follow, like, sign, vip）
     */
    public static function _check_update_exp($type)
    {
        $db = db('exp');
        $member = db('member');
        $userid = get_cookie('_userid');
    
        /**
         * 更新用户经验值和积分的函数
         *
         * @param string $type 更新类型
         */
        $updateExpAndPoint = function ($type) use ($db, $member, $userid) {
            $record = $db->where(['name' => $type])->find();
            $member->where(['userid' => $userid])
                ->setInc('experience',$record['value']);
            $member->where(['userid' => $userid])
                ->setInc('point',$record['point']);
            $info = Cache()->get('userinfo_key'.$userid);
            if(empty($info)) return json(['status' => 0, 'msg' => '数据缓存不存在!']);
            self::_check_update_group($userid,$info['experience'],$info['groupid'],$record['value']);
            self::_check_update_insert($record,$userid);
        };
    
        // 使用 switch 语句来处理不同的类型
        switch ($type) {
            case 'article':
                $updateExpAndPoint('article');
                break;
            case 'comment':
                $updateExpAndPoint('comment');
                break;
            case 'follow':
                $updateExpAndPoint('follow');
                break;
            case 'like':
                $updateExpAndPoint('like');
                break;
            case 'sign':
                $updateExpAndPoint('sign');
                break;
            case 'vip':
                $updateExpAndPoint('vip');
                break;
            default:
                // 可以添加默认操作或者不处理
                break;
        }
    }

    /**
	 * 根据用户经验检查并更新用户组
	 * @param integer $add           自增数量
	 * @param integer $experience    当前经验
	 * @param integer $userid        用户ID
	 */
	public static function _check_update_group($userid,$experience,$groupid,$add = '') 
	{
	    $new_groupid = 1;
		$data = db('group')->order('groupid ASC')->select();
        
		if(!$data) return false;
		$exp = $experience+$add;
		
		//如果超出用户组积分设置则为积分最高的用户组
		foreach ($data as $k=>$v) {
    		$experience_list[(int) $k + 1] = $v['experience'];
    	}
    	
    	arsort($experience_list);
    	if($exp > max($experience_list)) {
    		$new_groupid = key($experience_list);
    	} else {
    	    $tmp_k = null;
    		foreach ($experience_list as $k=>$v) {
    			if($exp >= $v) {
    				$new_groupid = $tmp_k;
    				break;
    			}
    			$tmp_k = $k;
    		}
    	}
        
		if($new_groupid != $groupid) {
			db('member')->where(['userid' => $userid])->update(['groupid'=>$new_groupid]);
		}
	}
	
	
    /**
     * 插入经验值记录
     *
     * @param array $array 包含 num 和 label 的数组
     * @param int $userid 用户ID
     */
    public static function _check_update_insert($array, $userid)
    {
        // 查询数据库中今天是否已经有记录
        $record = db('experience')->field('creat_time')->where(['userid'=>$userid])->find();
        $creat_time = $record ? $record['creat_time'] : 0;
        
        // 获取今天的0点的时间戳
        $starttime = strtotime(date('Y-m-d'));
        // 如果没有今天的记录，则插入数据
        if ($creat_time < $starttime) {
            $data = [
                'num' => $array['value'],
                'userid' => $userid,
                'creat_time' => time(),
                'msg' => $array['label'],
                'type' => '每日任务',
            ];
    
            db('experience')->insert($data);
        } else {
            // 如果已经有今天的记录，可以选择不执行任何操作或者执行其他操作
        }
    }
    
    
}