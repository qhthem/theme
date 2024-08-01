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
namespace extend\qq;

class Qq
{
    private $appid;
    private $appkey;
    private $callback;
    private $access_token;
    private $openid;

    /**
     * 构造函数，初始化 QQ 登录相关配置
     */
    public function __construct()
    {
        $this->appid = C('qq_appid');
        $this->appkey = C('qq_secret');
        $this->callback = C('qq_url');
        $this->access_token;
        $this->openid;
    }

    /**
     * 给外部调用的方法，供 QQ 登录
     */
    public function redirect_to_login()
    {
        $redirect = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=$this->appid&scope=&redirect_uri=" . rawurlencode($this->callback);
        return $redirect;
    }

    /**
     * 获取用户的 openid
     *
     * @param string $code 授权码
     * @return string 用户的 openid
     */
    public function get_openid($code)
    {
        $url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=$this->appid&client_secret=$this->appkey&code=$code&redirect_uri=" . rawurlencode($this->callback);
        $content = file_get_contents($url);
        if (stristr($content, 'access_token=')) {
            $params = explode('&', $content);
            $tokens = explode('=', $params[0]);
            $token = $tokens[1];
            $this->access_token = $token;
            Session()::set('access_token',$token);
            if ($token) {
                $url = "https://graph.qq.com/oauth2.0/me?access_token=$token";
                $content = file_get_contents($url);
                $content = str_replace('callback( ', '', $content);
                $content = str_replace(' );', '', $content);
                $returns = json_decode($content);
                $openid = $returns->openid;
                $this->openid = $openid;
                Session()::set('openid',$openid);
            } else {
                $openid = '';
            }
        } elseif (stristr($content, 'error')) {
            $openid = '';
        }
        return ['openid' =>Session()::get('openid'),'access_token'=>Session()::get('access_token')];
    }

    /**
     * 获取用户信息
     *
     * @return array 用户信息数组
     */
    public function get_user_info($token,$openid)
    {
        $url = "https://graph.qq.com/user/get_user_info?access_token=$token&oauth_consumer_key=$this->appid&openid=$openid";
        $content = file_get_contents($url);
        return json_decode($content, true);
    }
}