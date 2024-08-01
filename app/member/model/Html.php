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
use app\member\logic\Messages;

class Html {
    
    
    public static function page_type($type)
    {
        
        
        
    }
    
    public static function page_default_message(array $data,$type)
    {
        $html = '';
        $html .= '<div class="message-panel">
            <div class="head">
                <div class="back" @click="ll" style="position: absolute; left: 12px;">
                    <i class="ri-arrow-left-s-line" style="font-size: 20px;"></i>
                </div>
                <div class="title">'.(new Messages())->get_nickname($type).'</div></div>
            <div class="notice-list message-list">
                <div class="notice-list-content">';
        foreach ($data as $v) {
            $html .= '<div class="notice-item">
                        <div class="notice box">
                            <a target="_blank" rel="noopener noreferrer" class="avatar-link">
                                <div class="user-avatar">
                                    <img src="'.$v['userpic'].'" class="avatar-face w-h"></div>
                            </a>
                            <div class="notice-content">
                                <div class="notice-user">
                                    <a target="_blank" class="user-link">
                                        <span class="user-name">'.$v['nickname'].'</span></a>
                                </div>
                                <div class="notice-message">'.$v['content'].'</div>
                                <div class="notice-action">
                                    <span>'.$v['send_from'].'</span>
                                    <span class="notice-date">
                                        <time class="qk-timeago">'.$v['time'].'</time></span>
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        $html .= '</div></div></div></div>';
        return $html;
    }
    
    
    public static function page_userid_message(array $data,$userid)
    {
        $_userid = get_cookie('_userid');
        $html = '';
        $html .= '<div class="message-panel">
            <div class="head">
                <div class="back" @click="ll" style="position: absolute; left: 12px;">
                    <i class="ri-arrow-left-s-line" style="font-size: 20px;"></i>
                </div>
                <div class="title">'.(new Messages())->get_nickname('',$userid).'</div></div>
            <div class="notice-list message-list">
                <div class="notice-list-content">';
        foreach ($data as $v){
            $html .= '<div class="private-item">
                <div class="clearfix private-list ' . ($_userid == $v['send_to'] ? 'left' : 'right') . '">
                    <div class="avatar-img">
                        <img src="'.$v['userpic'].'" class="avatar"></div>
                    <span class="private-content main-bg comt-main">
                       '.$v['content'].'</span>
                </div>
            </div>
          ';
        }
        $html .= '</div></div>';
        return $html;
    }
    
    
    
    public static function page_wallet_message(array $data,$type)
    {
        $html = '';
        $html .= '<div class="message-panel">
            <div class="head">
                <div class="back" @click="ll" style="position: absolute; left: 12px;">
                    <i class="ri-arrow-left-s-line" style="font-size: 20px;"></i>
                </div>
                <div class="title">'.(new Messages())->get_nickname($type).'</div></div>
            <div class="notice-list message-list">
                <div class="notice-list-content">';
        foreach ($data as $v){
            $html .= '<div class="msg-notify">
            <div class="msg-notify-container">
                <div class="title">高级会员服务开通成功通知</div>
                <div class="content">恭喜您已开通30天高级会员服务，目前有效期至2024-07-02。</div>
                <div class="meta-list">
                    <div class="item">
                        <span>开通类型</span>
                        <span>任务活动</span></div>
                    <div class="item">
                        <span>当前状态</span>
                        <span>高级会员</span></div>
                </div>
            </div>
        </div>
          ';
        }
        $html .= '</div></div>';
        return $html;
    }    
    
}