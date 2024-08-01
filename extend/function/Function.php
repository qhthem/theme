<?php
// +----------------------------------------------------------------------
// | 自定义扩展函数
// +----------------------------------------------------------------------

/**
 * 获取缩略图
 *
 * @param string $thumb 缩略图路径
 * @param string $default 默认缩略图路径
 * @return string 返回缩略图路径
 * @author zhaosong
 */
if (!function_exists('get_thumb')) 
{
    function get_thumb($thumb, $default = ''):string
    {
        if ($thumb) return $thumb;
        return $default ? $default : '/static/images/thumb.png';
    }
}

/**
 * 获取默认模型
 *
 * @param int $modelid 模型ID
 * @param bool $type 模型类型，true 返回表名，false 返回模型ID
 * @return mixed 返回模型表名或模型ID
 * @author zhaosong
 */
if (!function_exists('get_model')) 
{
    function get_model($modelid = 0, bool $type = true):mixed
    {
        $where = empty($modelid) ? ['isdefault' => 1] : ['modelid' => $modelid];
        $content_tabname = get_model_where($where, $type ? 'tablename' : 'modelid');
        if(empty($content_tabname)){
            $content_tabname = get_model_where(['isdefault' => 1], $type ? 'tablename' : 'modelid');
        }
        return $content_tabname;
    }
}

/**
 * 根据条件获取模型信息
 *
 * @param array $param 查询条件
 * @param string $field 需要获取的字段
 * @return mixed 返回查询结果
 * @author zhaosong
 */
if (!function_exists('get_model_where')) 
{
    function get_model_where(array $param, string $field):mixed
    {
        $cacheKey = 'get_model_where_' . md5(json_encode($param)) . '_' . $field;
        $queryFunc = function() use ($param, $field) {
            return db('model')->where($param)->value($field);
        };
        
        return cache_get_or_set($cacheKey, $queryFunc);
    }
}

/**
 * 时间距离现在的格式化函数
 *
 * @param int $timestamp 时间戳
 * @return string 格式化后的时间距离现在的字符串
 * @author zhaosong
 */
if (!function_exists('time_ago')) {
    function time_ago($timestamp)
    {
        $now = time(); // 获取当前时间戳
        $diff = $now - $timestamp; // 计算时间差

        // 计算时间差的年、月、日、小时、分钟和秒
        $years = floor($diff / (365 * 24 * 60 * 60));
        $months = floor(($diff - $years * 365 * 24 * 60 * 60) / (30 * 24 * 60 * 60));
        $days = floor(($diff - $years * 365 * 24 * 60 * 60 - $months * 30 * 24 * 60 * 60) / (24 * 60 * 60));
        $hours = floor(($diff - $years * 365 * 24 * 60 * 60 - $months * 30 * 24 * 60 * 60 - $days * 24 * 60 * 60) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 24 * 60 * 60 - $months * 30 * 24 * 60 * 60 - $days * 24 * 60 * 60 - $hours * 60 * 60) / 60);
        $seconds = $diff - $years * 365 * 24 * 60 * 60 - $months * 30 * 24 * 60 * 60 - $days * 24 * 60 * 60 - $hours * 60 * 60 - $minutes * 60;

        // 根据时间差生成相应的字符串
        $time_ago = '';
        if ($years > 0) {
            $time_ago = $years . '年前';
        } elseif ($months > 0) {
            $time_ago = $months . '月前';
        } elseif ($days > 0) {
            $time_ago = $days . '天前';
        } elseif ($hours > 0) {
            $time_ago = $hours . '小时前';
        } elseif ($minutes > 0) {
            $time_ago = $minutes . '分钟前';
        } else {
            $time_ago = '刚刚';
        }

        return $time_ago;
    }
}

/**
 * 判断用户登录信息是否存在
 *
 * @param int $userid 用户ID
 * @return mixed|null 如果存在，返回用户信息；否则返回null
 */
if (!function_exists('is_userinfo')) {
    function is_userinfo()
    {
        $userinfo = [
            '_userid'=> get_cookie('_userid'),
		    '_username'=> get_cookie('_username'),
		    '_groupid'=> get_cookie('_groupid'),
		    '_nickname'=> get_cookie('_nickname'),
		    '_vip'=> get_cookie('_vip'),
        ];
        
       return empty($userinfo) ? [] :$userinfo;
    }
}

/**
 * 添加或删除用户信息cookie
 *
 * @param array $data 用户信息数组，包含userid, username, groupid, nickname, vip
 * @param bool $add 是否添加cookie
 * @param bool $del 是否删除cookie
 * @return bool 操作成功返回true
 */
if (!function_exists('add_del_userinfo_cookie')) {
    function add_del_userinfo_cookie(array $data = [], bool $add = false, bool $del = false)
    {
        if ($add) {
            cookie('_userid', $data['userid']);
            cookie('_username', $data['username']);
            cookie('_groupid', $data['groupid']);
            cookie('_nickname', $data['nickname']);
            cookie('_vip', $data['vip']);

            return true;
        }

        if ($del) {
            del_cookie('_userid');
            del_cookie('_username');
            del_cookie('_groupid');
            del_cookie('_nickname');
            del_cookie('_vip');

            return true;
        }

        return true;
    }
}

/**
 * 获取用户信息
 *
 * @param int $userid 用户ID
 * @param string $field 要获取的字段名
 * @return mixed|null 如果存在，返回指定字段的值；否则返回null
 */
if (!function_exists('get_userinfo')) {
    function get_userinfo($userid, string $field = '') {
        $cacheKey = 'userinfo_' . $userid . '_' . $field;
        $member = db('member');
        
        if (!empty($field)) {
            $queryFunc = function() use ($userid, $field,$member) {
                return $member->where(['userid' => $userid])->value($field);
            };
        } else {
            $queryFunc = function() use ($userid, $field,$member) {
                return $member->where(['userid' => $userid])->find();
            };
        }
        
        return cache_get_or_set($cacheKey, $queryFunc);
    }
}

/**
 * 获取用户头像
 *
 * @param int $userid 用户ID
 * @return string 如果存在头像，返回头像路径；否则返回默认头像路径
 */
if (!function_exists('get_avatar')) {
    function get_avatar($userid) {
        $avatar = get_userinfo($userid, 'userpic');
        return !empty($avatar) ? $avatar : '/static/images/default-avatar.png';
    }
}

/**
* 根据用户ID获取用户等级对应的图标路径
*
* @param int $userid 用户ID
* @return string 用户等级对应的图标路径
*/
if (!function_exists('get_userlv')) {
    function get_userlv($userid) {
        // 调用get_userinfo函数，获取用户的groupid（用户级别）
        $lv = get_userinfo($userid, 'groupid');
        // 根据用户级别，生成对应的图标路径，并返回
        return '/static/icon/lv'.$lv.'.png';
    }
}

/**
 * 将电子邮件地址中的部分字符替换为星号（*）。
 *
 * @param string $email 要掩码的电子邮件地址
 * @return string 掩码后的电子邮件地址
 */
if (!function_exists('maskEmail')){
    function maskEmail($email) {
        $atPosition = strpos($email, '@');
        $domain = substr($email, $atPosition + 1);
        $username = substr($email, 0, $atPosition);
        $usernameLength = strlen($username);
        // 如果用户名长度大于2，则保留前2个字符和最后一个字符，中间的字符替换为星号
        if ($usernameLength > 2) {
            $maskedUsername = substr($username, 0, 2) . str_repeat('*', $usernameLength - 2);
        } else {
            $maskedUsername = $username;
        }
    
        return $maskedUsername . '@' . $domain;
    }    
}

/**
 * 获取内容评论数
 *
 * @param  int $id
 * @param  int $catid
 * @param  int $modelid
 * @return string
 */
if (!function_exists('get_comment_total')){
    function get_comment_total($id,$modelid = 1){
    	if(!$id) return 0;
    	$total = db('comments')->where(['modelid'=>$modelid,'aid'=>$id])->count();
    	return $total ? $total : 0;
    }
}

if (!function_exists('is_follow')) {
    /**
     * 判断用户是否关注了另一个用户
     * @author zhaosong
     * @param int $userid 用户ID
     * @param int $followid 被关注用户ID
     * @return int 1表示已关注，0表示未关注
     */
    function is_follow($userid, int $followid) {
        if(empty($userid)) $userid = 0;
        $data = db('follow')
            ->where(['userid' => $userid, 'followid' => $followid])
            ->find();
        return $data ? 1 : 0;
    }
}

if (!function_exists('get_category')) {
    /**
     * 获取分类信息
     * @author zhaosong
     * @param int $catid 分类ID
     * @param string $field 需要获取的字段名
     * @return string 分类信息
     */
    function get_category($catid, $field = '') {
        $cacheKey = 'category_' . $catid . '_' . $field;
    
        if (empty($field)) {
            $queryFunc = function() use ($catid) {
                return db('category')->where(['catid' => $catid])->find();
            };
        } else {
            $queryFunc = function() use ($catid, $field) {
                return db('category')->where(['catid' => $catid])->value($field);
            };
        }
    
        return cache_get_or_set($cacheKey, $queryFunc);
    }
}

/**
 * 检查给定的分类ID是否有子分类
 *
 * @param int $catid 分类ID
 * @return array|bool 如果有子分类，返回子分类信息数组；否则返回false
 */
if (!function_exists('get_childid')) {
    function get_childid($catid,$arrchildid)
    {
    	if (empty($catid)) return false;
        if (empty($arrchildid)) return false;
        $categoryinfo = db('category')->where(['catid' => ['in' => explode(',',$arrchildid)],'status'=>1])->select();
        return $categoryinfo;
    }
}


if (!function_exists('is_favorite')) {
    /**
     * 检查是否收藏、添加收藏或删除收藏
     * @author zhaosong
     * @param int $id 内容ID
     * @param int $modelid 模型ID
     * @param int $catid 分类ID
     * @param bool $del 是否删除收藏
     * @param bool $add 是否添加收藏
     * @param bool $count 是否返回收藏数量
     * @return int|bool 返回1表示已收藏，0表示未收藏，布尔值表示操作成功与否
     */
    function is_favorite(int $id, int $modelid, int $catid, bool $del = false, bool $add = false, bool $count = false)
    {
        $userid = get_cookie('_userid');
        $favorite = db('favorite');
        $where = ['aid' => $id, 'modelid' => $modelid];
        $cacheKey = "is_favorite_{$id}_{$modelid}_{$catid}_{$userid}";
        $data = $favorite->where($where)->find();
        
        if ($del) {
            $result = $favorite->where($where)->delete();
            return $count ? $favorite->where($where)->count() : $result;
        } elseif ($add) {
            $result = $favorite->insert([
                'userid' => $userid,
                'aid' => $id,
                'modelid' => $modelid,
                'catid' => $catid,
                'inputtime' => time(),
            ]);
            return $count ? $favorite->where($where)->count() : $result;
        } else {
            return $data ? 1:0;
        }
    }
}

if (!function_exists('check_ip_matching')){
    /**
     * 检查IP地址是否匹配
     *
     * @param string $ip_vague 模糊IP地址，如：192.168.1.*
     * @param string $ip 待检查的IP地址，如果为空，则使用当前访问者的IP地址
     * @return bool 如果匹配返回true，否则返回false
     * @author zhaosong
     */
    function check_ip_matching($ip_vague, $ip = ''){
        empty($ip) && $ip = getip();
        if(strpos($ip_vague,'*') === false){
            return $ip_vague == $ip;
        }
        if(count(explode('.', $ip_vague)) != 4) return false;
        $min_ip = str_replace('*', '0', $ip_vague);
        $max_ip = str_replace('*', '255', $ip_vague);
        $ip = ip2long($ip);
        if($ip>=ip2long($min_ip) && $ip<=ip2long($max_ip)){  
            return true; 
        }
        return false;
    }    
}

if (!function_exists('html_special_chars')){
    /**
     * 返回经htmlspecialchars处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @param $filter 需要排除的字段，格式为数组
     * @return mixed
     */
    function html_special_chars($string, $filter = array()) {
    	if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,'utf-8');
    	foreach($string as $key => $val){
    		$string[$key] = $filter&&in_array($key, $filter) ? $val : html_special_chars($val, $filter);
    	}
    	return $string;
    }    
}

if (!function_exists('random')) {
    /**
     * 生成指定长度的随机字符串
     *
     * @param int    $length 字符串长度，默认为 4
     * @param string $chars  可选字符集，默认为数字
     * @return string 生成的随机字符串
     * @author zhaosong
     */
    function random(int $length = 4, string $chars = '0123456789')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return date('YmdHis') . $hash;
    }
}

/**
 * 检查用户是否具有访问特定路由的权限
 * 如果函数不存在，则定义该函数
 *
 * @author zhaosong
 */
if (!function_exists('is_group_auth')) {
    /**
     * 检查用户是否具有访问特定路由的权限
     *
     * @return void
     * @author zhaosong
     */
    function is_group_auth()
    {
        // 获取当前用户的群组ID
        $groupid = get_cookie('_groupid');
        $userid = get_cookie('_userid');
        // 获取当前路由信息
        $app = GetRoute('app');
        $controller = strtolower(GetRoute('controller'));
        $action = GetRoute('action');

        // 尝试从缓存中获取权限信息
        $cacheKey = "group_auth_{$groupid}_{$app}_{$controller}_{$action}_{$userid}";
        // 缓存中没有数据，从数据库查询
        $authority = cache_get_or_set($cacheKey, function() use ($groupid) {
            return db('group')->where(['groupid' => $groupid])->value('authority');
        }, 7200);
        
        $db = db('group_auth');
        $data = cache_get_or_set($cacheKey.'_auth', function() use ($db,$app,$controller,$action){
            return $db->where(['app' => $app,'controller' => $controller,'action' => $action])
            ->find();
        }, 7200);
        
        if (!empty($data)) {
            $numberArray = explode(',', $authority);
            if (in_array($data['id'], $numberArray)) {
                // 用户有权限
            } else {
                showmsg('会员组权限不足!', false, 0);
            }
        }
    }
}

/**
 * 遍历函数，用于创建一个新的 Traverse 实例。
 * 如果已经存在同名函数，则不会重新定义。
 * 
 * @author zhaosong
 * @return \extend\traverse\Traverse 返回一个新的 Traverse 实例
 */
if (!function_exists('Traverse')) {
    function Traverse()
    {
        // 创建并返回 Traverse 类的实例
        return new extend\traverse\Traverse;
    }
}