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
namespace app\member\controller;
use app\member\logic\Api as Apilogic;
use app\admin\logic\Content as C;
use app\admin\logic\Upload as U;
use app\member\model\Sign;
use app\member\model\Alipay;

class Api
{
    /**
     * 记录接口
     *
     * @author zhaosong
     */
    public function record(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::recordlogic(Request()::post('type')));
        }
    }

    /**
     * 充值接口
     *
     * @author zhaosong
     */
    public function Recharge(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::Rechargelogic(Request()::post('type')));
        }
    }

    /**
     * 成长值接口
     *
     * @author zhaosong
     */
    public function growth(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::growthlogic(Request()::post('type')));
        }
    }

    /**
     * 设置接口
     *
     * @author zhaosong
     */
    public function setting(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::settinglogic(Request()::post()));
        }
    }

    /**
     * 用户头像接口
     *
     * @author zhaosong
     */
    public function userpic(): void
    {
        if (Request()::isPost()) {
            json(Apilogic::userpiclogic(Request()::file('image')));
        }
    }

    /**
     * 获取分类ID
     *
     * @author zhaosong
     */
    public function getcatid()
    {
        if (Request()::isPost()) {
            json(Apilogic::catidlogic());
        }
    }

    /**
     * 添加接口
     *
     * @author zhaosong
     */
    public function add()
    {
        if (Request()::isPost()) {
            json(C::addlogic(Request()::post()));
        }
    }

    /**
     * 图片盒子接口
     *
     * @author zhaosong
     */
    public function image_box()
    {
        if (Request()::isPost()) {
            json(U::listlogic(Request()::post()));
        }
    }

    /**
     * 图片上传接口
     *
     * @author zhaosong
     */
    public function image_upload()
    {
        if (Request()::isPost()) {
            json(Apilogic::uploadlogic(Request()::file('image')));
        }
    }

    /**
     * 签到接口
     *
     * @author zhaosong
     */
    public function sign()
    {
        if (Request()::isPost()) {
            json((new Sign())->index());
        }
    }

    /**
     * 账户接口
     *
     * @author zhaosong
     */
    public function account()
    {
        if (Request()::isPost()) {
            json(Apilogic::accountlogic(Request()::post()));
        }
    }

    /**
     * 购买积分接口
     *
     * @author zhaosong
     */
    public function buy_point()
    {
        if (Request()::isPost()) {
            json(Apilogic::pointlogic(Request()::post()));
        }
    }
    
    /**
     * 支付宝异步回调
     *
     * @author zhaosong
     */
    public function public_notify_alipay()
    {
        if (Request()::isPost()) {
            json(Alipay::public_notify_alipay());
        }
    }
    
    /**
     * 查询用户订单状态
     *
     * @author zhaosong
     */
    public function order_status()
    {
        if (Request()::isPost()) {
            json(Alipay::order_status(Request()::post('out_trade_no')));
        }
    }
    
    /**
     * 购买余额接口
     *
     * @author zhaosong
     */
    public function buy_amount()
    {
        if (Request()::isPost()) {
            json(Apilogic::amountlogic(Request()::post()));
        }
    }

    /**
     * 搜索用户接口
     *
     * @author zhaosong
     */
    public function search_user()
    {
        if (Request()::isPost()) {
            json(Apilogic::userlogic(Request()::post('s')));
        }
    }

    /**
     * 转账接口
     *
     * @author zhaosong
     */
    public function transfer()
    {
        if (Request()::isPost()) {
            json(Apilogic::Transferlogic(Request()::post()));
        }
    }

    /**
     * 关注接口
     *
     * @author zhaosong
     */
    public function follow()
    {
        if (Request()::isPost()) {
            json(Apilogic::followlogic(Request()::post('followid')));
        }
    }
    
    
    
    /**
     * 绑定接口
     *
     * @author zhaosong
     */    
    public function bind()
    {
        if (Request()::isGet()) {
            Apilogic::bindlogic(Request()::get());
        }
    }     
    
    
    /**
     * 解除绑定接口
     *
     * @author zhaosong
     */    
    public function unbind()
    {
        if (Request()::isGet()) {
            Apilogic::unbindlogic(Request()::get());
        }
    }
    
    
    /**
     * 获取验证码接口
     *
     * @author zhaosong
     */
    public function send_code()
    {
        if (Request()::isPost()) {
            json(Apilogic::send_code(Request()::post()));
        }
    }   
    
    
    
    /**
     * 获取订单列表接口
     *
     * @author zhaosong
     */
    public function order_list()
    {
        if (Request()::isPost()) {
            json(Apilogic::order_list(Request()::post()));
        }
    }  
    
     
    
}