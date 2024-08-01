
const login_template =  `<div class="login-container" style="max-width: 363px;">
  <div class="container-top">
        <div class="title">{{ !boxtype ? '欢迎回来':'注册新账号' }}</div></div>
    <div class="container-content form-container">
        <form>
            <div class="login-box">
                <div class="mb-3">
                    <input v-model="username" class="form-control" placeholder="请输入用户名"></div>
                <div class="mb-3" v-if="boxtype">
                    <input type="email" v-model="email" class="form-control" placeholder="请输入邮箱"></div>
                <div class="mb-3">
                    <input type="password" v-model="password" class="form-control" placeholder="请输入密码"></div>
                <div class="signin-hint mb-3">
                    <div class="hint-text">还没有账号?
                        <span @click="boxtype = !boxtype">{{ !boxtype ? '前往注册':'前往登录' }}</span></div>
                    <a class="forget-password">忘记密码？</a></div>
                <div class="form-button d-grid">
                    <button v-if="!boxtype" type="button" @click="login_subimt" class="btn btn-primary">登录</button>
                    <button v-else type="button" @click="register_subimt" class="btn btn-primary">注册</button></div>
            </div>
        </form>
    </div>
    <div class="social-loginbar">
        <div class="separator-text">或</div>
        <div class="other-login">
            <a href="/member/index/qq_login" class="no-hover qq">
                <i class="ri-qq-fill"></i>
            </a>
            <a href="javascript:void(0)" class="no-hover weibo">
                <i class="ri-weibo-fill"></i>
            </a>
            <a href="javascript:void(0)" class="no-hover weixin">
                <i class="ri-wechat-fill"></i>
            </a>
        </div>
        <div class="agreement" style="">登录表示您已阅读并同意
            <span>
                <a>用户协议</a>
            </span>和
            <span>
                <a>隐私政策</a>
            </span>
        </div>
    </div>
</div> `;

const pagination_template = `<div class="qh-pagenav post-nav" v-if="totalItems>0">
    <div class="qh-ajax-pager">
        <div class="ajax-pagination">
            <button type="button" class="btn-prev" @click="previousPage" :disabled="currentPage === 1">❮</button>
            <ul class="qh-pager">
                <li @click="changePage(pageNumber)" v-for="pageNumber in totalPages" :key="pageNumber" :class="{'number': true, 'active': pageNumber === currentPage }" :disabled="pageNumber === currentPage ? 'disabled' : null">{{ pageNumber }}</li></ul>
            <button type="button" class="btn-prev" @click="nextPage" :disabled="currentPage === totalPages">❯</button></div>
    </div>
</div>`;

const model_template = `<div v-if="show" :class="{ 'modal qh-modal fade': true, 'show': show }" :style="{ 'display': show ? 'block' : 'none' }">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="close">
                <i class="ri-close-fill" @click="$emit('close',false)"></i>
            </div>
            <component :is="name" :params="params"></component>
        </div>
    </div>
</div>`;

const moeny_template = `<div class="recharge-container" v-if="qrcode.length == 0">
    <div class="title">余额充值</div>
    <div class="fast-amount-list">
        <div class="em14 flex jc h200" v-if="status">
            <span class="ant-spin-dot ant-spin-dot-spin">
                <i class="ant-spin-dot-item"></i>
                <i class="ant-spin-dot-item"></i>
                <i class="ant-spin-dot-item"></i>
                <i class="ant-spin-dot-item"></i>
            </span>
        </div>
        <ul class="list" v-else>
            <li :class="number == item.value ? 'item active':'item'" v-for="(item,i) in moeny" @click="number = item.value">
                <div class="price-icon" v-if="item.discount>0">{{ item.discount}}折</div>
                <div class="price-name">{{ item.label}}
                    <span class="currency">元</span></div>
                <div class="price-num">￥{{ item.value}}</div></li>
        </ul>
    </div>
    <div class="pay-type">
        <div class="title">请选择付款方式</div>
        <ul class="qh-flex">
            <li :class="paytype == 'alipay'? 'active':''" @click="paytype = 'alipay'">
                <i class="ri-alipay-fill"></i>支付宝</li>
            <li :class="paytype == 'wechat'? 'active':''" @click="paytype = 'wechat'">
                <i class="ri-wechat-pay-fill"></i>微信</li>
            <li :class="paytype == 'wallet'? 'active':''" @click="paytype = 'wallet'">
                <i class="ri-wallet-3-fill"></i>卡密</li>
        </ul>
    </div>
    <div class="pay-button">
        <button @click="buyamount">
            <span>立即支付 {{ number ? number :0 }}元</span></button>
    </div>
</div>
<div class="qrcode-pay" v-else>
    <div class="scan-info">
        <div class="qrcode-img qh-radius">
            <img :src="qrcode"></div>
        <div class="qrcode-money">
            <span>￥{{ number }}</span></div>
        <div class="qrcode-desc">请打开手机使用
            <span class="scan-alipay">支付宝</span>扫码支付</div>
        <div class="qrcode-time" id="timer">「{{ countdown }}」</div></div>
</div>`;

const point_template = `<div class="recharge-container" v-if="qrcode.length == 0">
    <div class="title">购买积分</div>
    <div class="fast-amount-list">
        <div class="em14 flex jc h200" v-if="status">
            <span class="ant-spin-dot ant-spin-dot-spin">
                <i class="ant-spin-dot-item"></i>
                <i class="ant-spin-dot-item"></i>
                <i class="ant-spin-dot-item"></i>
                <i class="ant-spin-dot-item"></i>
            </span>
        </div>
        <ul class="list" v-else>
            <li :class="number == item.value ? 'item active':'item'" v-for="(item,i) in moeny" @click="number = item.value,label = item.label">
                <div class="price-icon" v-if="item.discount>0">{{ item.discount}}折</div>
                <div class="price-name">{{ item.label}}
                    <span class="currency">积分</span></div>
                <div class="price-num">￥{{ item.value}}</div></li>
        </ul>
    </div>
    <div class="pay-type">
        <div class="title">请选择付款方式</div>
        <ul class="qh-flex">
            <li :class="paytype == 'alipay'? 'active':''" @click="paytype = 'alipay'">
                <i class="ri-alipay-fill"></i>支付宝</li>
            <li :class="paytype == 'wechat'? 'active':''" @click="paytype = 'wechat'">
                <i class="ri-wechat-pay-fill"></i>微信</li>
            <li :class="paytype == 'balance'? 'active':''" @click="paytype = 'balance'">
                <i class="ri-wallet-3-fill"></i>余额</li>
            <li :class="paytype == 'wallet'? 'active':''" @click="paytype = 'wallet'">
                <i class="ri-wallet-3-fill"></i>卡密</li>
        </ul>
    </div>
    <div class="pay-button">
        <button @click="buypoint">
            <span>立即支付 {{ number ? number :0 }}元</span></button>
    </div>
</div>
<div class="qrcode-pay" v-else>
    <div class="scan-info">
        <div class="qrcode-img qh-radius">
            <img :src="qrcode"></div>
        <div class="qrcode-money">
            <span>￥{{ number }}</span></div>
        <div class="qrcode-desc">请打开手机使用
            <span class="scan-alipay">支付宝</span>扫码支付</div>
        <div class="qrcode-time" id="timer">「{{ countdown }}」</div></div>
</div>`;

const transfer_point_template = `<div class="withdrawal-container recharge-container">
    <div class="colorful-header qh-flex">
        <div class="title">积分转账</div></div>
    <div class="transfer" v-if="userinfo.length === 0">
        <input type="text" @input="search_list" class="form-control search-input" placeholder="请输入关键词以搜索用户">
        <div class="mt20 mb10 muted-3-color separator search-remind em09">{{ status }}</div>
        <div class="search-container mini-scrollbar scroll-y max-vh5">
            <div class="flex ac padding-h6 qh-border-bottom pointer" v-for="item in user_list" v-if="user_list.length != 0" @click="userinfo = item">
                <a :href="item.url">
                    <span class="avatar-img">
                        <img :src="item.userpic" class="avatar radius-cover"></span>
                </a>
                <div class="flex1 ml10 flex ac jsb">
                    <div class="flex1">
                        <div class="flex ac flex1">
                            <a class="display-name text-ellipsis" :href="item.url">{{ item.nickname }}</a>
                            <img class="img-icon ml3 ls-is-cached" :src="item.group"></div>
                        <div class="mt3 em09 muted-2-color">{{ item.email }}</div></div>
                    <div class="flex0 em09 ml10 pointer">
                        <div class="muted-color">给他转账
                            <i style="margin:0 0 0 6px;" class="ri-arrow-right-s-line em12"></i></div>
                    </div>
                </div>
            </div>
            <div class="text-center" style="padding:40px 0;" v-else>
                <img style="height:150px;opacity: .7;" src="/static/them/images/Curious-cuate.svg"></div>
        </div>
    </div>
    <div class="transfer_point pd16" v-else>
        <div class="mb10">
            <a @click="userinfo = []" class="muted-color em14 pointer">
                <i class="ri-arrow-left-s-line em12 mr6"></i>重新选择用户</a>
        </div>
        <div class="transfer-user">
            <span class="flex ac muted-box" name="recipient">
                <a :href="userinfo.url">
                    <span class="avatar-img">
                        <img :src="userinfo.userpic" class="avatar radius-cover"></span>
                </a>
                <div class="flex1 ml10 flex ac jsb">
                    <div class="flex1">
                        <div class="flex ac flex1">
                            <a class="display-name text-ellipsis" :href="userinfo.url">{{ userinfo.nickname}}</a>
                            <img class="img-icon ml3" :src="userinfo.group"></div>
                        <div class="mt3 em09 muted-2-color">{{ userinfo.email }}</div></div>
                    <div class="flex0 em09 ml10 recipient-user-btn">
                        <div class="muted-color">给他转账
                            <i style="margin:0 0 0 6px;" class="ri-arrow-right-s-line em12"></i></div>
                    </div>
                </div>
            </span>
        </div>
        <div class="muted-color mt20 mb6 em14">请输入转账金额</div>
        <div class="active">
            <div class="relative flex ab">
                <span class="ml6 mr10 muted-color shrink0">
                    <i class="ri-database-2-line"></i>
                </span>
                <input class="line-form-input em16 key-color" style="padding: 1px;" v-model="userinfo.number" type="number" min="1">
                <i class="line-form-line"></i>
            </div>
        </div>
        <div class="muted-box muted-2-color padding-10 mb10 em14 mt6">
            <div>转账操作无法撤回，请认真核对转账用户及金额</div>
            <div>请正确使用积分，请勿用于刷积分等违规操作</div>
            <div>如有疑问，请与客服联系</div>
            <div class="mt6 c-yellow-2">转账时将扣除10%的手续费</div></div>
        <div class="form-button pay-button" style="padding-bottom: 0;">
            <button class="but c-bule" @click="Transfer">立即转账</button></div>
    </div>
</div>`;

const account_password_template = `<div class="binding-container">
    <div class="colorful-header qh-flex">
        <div class="title">
            <i class="ri-lock-password-line"></i>
            <div>修改密码</div></div>
    </div>
    <div class="form-container">
        <form>
            <label class="form-item">
                <input v-model="params.password" class="form-control" type="password" placeholder="新密码"></label>
            <label class="form-item">
                <input type="password" v-model="params.password2" class="form-control" placeholder="确认密码"></label>
            <div class="form-button">
                <button type="button" @click="submit_password()">确认</button></div>
        </form>
    </div>
</div>`;

const account_email_template = `<div class="binding-container">
    <div class="colorful-header qh-flex">
        <div class="title">
            <i class="ri-mail-settings-line"></i>
            <div>绑定邮箱</div></div>
    </div>
    <div class="form-container">
        <form>
            <label class="form-item">
                <input class="form-control" v-model="params.email" type="email" placeholder="请输入邮箱"></label>
            <label class="form-item">
                <input type="text" v-model="params.code" class="form-control" placeholder="请输入验证码" style="width: 70%;">
                <div class="login-eye text" v-if="!countdown" @click="startCountdown">获取验证码</div>
                <div class="login-eye text" v-else>剩余: {{ countdown }} 秒</div></label>
            <div class="form-button">
                <button type="button" @click="submit_email()">确认</button></div>
        </form>
    </div>
</div>`;

const account_phone_template = `<div class="binding-container">
    <div class="colorful-header qh-flex">
        <div class="title">
            <i class="ri-phone-line"></i>
            <div>绑定手机号</div></div>
    </div>
    <div class="form-container">
        <form>
            <label class="form-item">
                <input class="form-control" v-model="params.phone" type="text" placeholder="请输入手机号"></label>
            <label class="form-item">
                <input type="text" class="form-control" v-model="params.code" placeholder="请输入验证码" style="width: 70%;">
                <div class="login-eye text" v-if="!countdown" @click="startCountdown">获取验证码</div>
                <div class="login-eye text" v-else>剩余: {{ countdown }} 秒</div></label>
            <div class="form-button">
                <button type="button" @click="submit_phone()">确认</button></div>
        </form>
    </div>
</div>`;

const user_follow_template = `<button :class="clask" @click="submit_follow(userid)">
    <i class="ri-heart-3-line" v-if="status === 0"></i>
    <i class="ri-heart-3-fill" v-else></i>{{ status ? '已关注' : '关注' }}</button>`;
    
const user_msg_template = `<div class="withdrawal-container" style="width: 363px;">
    <div class="colorful-header qh-flex">
        <div class="title">给{{ params.nickname }}发送消息</div></div>
    <div class="content-wrap pd16">
        <div class="mb6">
            <textarea v-model="content" class="form-control" rows="5"></textarea>
        </div>
        <div class="pull-right mb6">
            <button class="but c-blue send-private pw-1em" @click="submit_msg">
                <i class="ri-navigation-line"></i>发送</button>
        </div>
    </div>
</div>`;

const side_template = `<div :num="likeCount" :class="active == 'a_l'? 'like qh-flex box active':'like qh-flex box'" @click="submit('likes')">
    <i class="ri-thumb-up-line"></i>
</div>
<div :num="params.comment" class="comment qh-flex box">
    <i class="ri-message-3-line"></i>
</div>
<div :num="favoriteCount" :class="active == 'f_d' ? 'collect qh-flex box active':'collect qh-flex box'" @click="submit('favorite')">
    <i class="ri-star-smile-line"></i>
</div>
<div class="share qh-flex box">
    <i class="ri-share-circle-line"></i>
</div>`;

const comment_post_template = `<div class="comment-send">
    <div class="comment-user-avatar">
        <div class="user-avatar" v-if="!replyCommentId">
            <img :src="params.userpic" class="avatar-face w-h"></div>
    </div>
    <div class="comment-textarea-container">
        <textarea class="form-control" v-model="params.textarea" placeholder="只是一直在等你而已，才不是想被评论呢～"></textarea>
        <div class="comment-button">
            <div class="comment-botton-left" v-if="replyCommentId">
                <span class="comment-emoji" @click="close">取消回复</span></div>
            <button type="button" class="comment-submit" @click="submit">评论</button></div>
    </div>
</div>`;

const comment_template = `<div v-for="items in params">
    <li class="comment-item">
        <figure class="comment-avatar">
            <div class="user-avatar">
                <img :src="items.userpic" class="avatar-face w-h"></div>
        </figure>
        <div class="comment-container">
            <div class="comment-user-info">
                <div class="user-info-name">
                    <a target="_blank" :href="items.user_url" class="user-name no-hover">{{ items.nickname }}</a>
                    <span class="user-lv">
                        <img :src="items.lv" class="lv-img-icon"></span>
                </div>
            </div>
            <div itemprop="text" class="comment-content">
                <div class="comment-content-text">回复
                    <a target="_blank" :href="items.reply_url" class="user-name">@{{ items.reply_name }}</a>：
                    <p>{{ items.content }}</p>
                </div>
                <div class="comment-details">
                    <span class="comment-time">
                        <time class="qh-timeago">{{ items.creat_time }}</time></span>
                    <span class="comment-reply" @click="reply(items.userid,items.aid,items.id)">回复</span></div>
            </div>
            <comment_post :params="items" v-if="replyCommentId === items.id" /></div>
    </li>
    <div v-if="items.children.length > 0">
       <comment :params="items.children" />
    </div>
    
</div>
`;

const comment_list_template = `<section class="comments-area box mt-3 qh-radius">
    <comment_post :params="params" v-if="editor == true" />
    <div class="comment-orderby">
        <div class="comment-orderby-left">
            <span class="active">全部评论</span></div>
        <div class="comment-orderby-rigth">
            <span :class="active == 'creat_time DESC' ?'active':'' " @click="getlist('creat_time DESC')">最新</span>
            <span :class="active == 'creat_time ASC' ?'active':'' " @click="getlist('creat_time ASC')">热门</span></div>
    </div>
    <div class="comments-area-content">
        <ol class="comment-list">
            <li class="comment-item" v-for="item in list" v-if="list.length>0">
                <figure class="comment-avatar">
                    <div class="user-avatar">
                        <img :src="item.userpic" class="avatar-face w-h"></div>
                </figure>
                <div class="comment-container">
                    <div class="comment-user-info">
                        <div class="user-info-name">
                            <a target="_blank" :href="item.user_url" class="user-name no-hover">{{ item.nickname }}</a>
                            <span class="user-lv">
                                <img :src="item.lv" class="lv-img-icon"></span>
                        </div>
                    </div>
                    <div itemprop="text" class="comment-content">
                        <div class="comment-content-text">
                            <p>{{ item.content }}</p>
                        </div>
                        <div class="comment-details">
                            <span class="comment-time">
                                <time class="qh-timeago">{{ item.creat_time }}</time></span>
                            <span class="comment-reply" @click="reply(item.userid,item.aid,item.id)">回复</span></div>
                    </div>
                    <ul class="children" v-if="item.children.length>0">
                        <comment :key="item.id" :params="item.children"></comment>
                    </ul>
                    <comment_post :params="params" v-if="replyCommentId === item.id" /></div>
            </li>
            <li class="comment-list-empty" v-else>
                <img style="width: 30%" src="/static/them/images/Nodata-bro.svg"></li>
        </ol>
    </div>
    <div class="qh-pagenav comment-nav"></div>
</section>`;

const record_limit_template = `<div class="tabs">
    <ul class="tabs-nav">
        <li :class="type == 0 ? 'active':''">
            <a href="javascript:;" @click="getRecord(0)">入账记录</a></li>
        <li :class="type == 1 ? 'active':''">
            <a href="javascript:;" @click="getRecord(1)">支出记录</a></li>
    </ul>
    <div class="em14 flex jc h200" v-if="status">
        <span class="ant-spin-dot ant-spin-dot-spin">
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
        </span>
    </div>
    <div class="tab-content" v-else>
        <div class="record-list">
            <div class="empty-page" v-if="Record.length == 0">
                <img src="/static/them/images/Curious-rafiki.svg">
                <p class="em14">暂无相关结果</p></div>
            <div class="record-item" v-for="item in Record">
                <div class="record-type">
                    <div class="record-title">{{ item.msg}}</div>
                    <div class="record-value credit">
                        <b class="mr5">{{ item.type ? '-':'+'}} {{ item.money}}</b>{{ item.paytype}}</div></div>
                <div class="record-detail">
                    <div class="record-desc">{{ item.trade_sn }}</div>
                    <div class="record-date">{{ item.creat_time}}</div></div>
            </div>
        </div>
    </div>
</div>`;

const growth_list_template = `<div class="tabs">
    <ul class="tabs-nav">
        <li :class="type == 0 ? 'active':''" @click="getgrowth(0)">经验任务</li>
        <li :class="type == 1 ? 'active':''" @click="getgrowth(1)">经验记录</li>
        <div class="active-bar"></div>
    </ul>
    <div class="em14 flex jc h200" v-if="status">
        <span class="ant-spin-dot ant-spin-dot-spin">
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
        </span>
    </div>
    <div class="tabs-content" v-else>
        <div class="task-section" v-if="!type">
            <div class="title">每日任务</div>
            <div class="task-list">
                <div class="list-item" v-for="item in list">
                    <div class="item-left">
                        <div class="task-title">
                            <div>{{ item.label}}</div>
                            <div class="task-prize">
                                <div class="prize-item">
                                    <span class="pioints credit">
                                        <i class="ri-money-pound-circle-line"></i>+{{ item.value}}</span>
                                </div>
                                <div class="prize-item">
                                    <span class="pioints exp c-blue-2">
                                        <i class="ri-exchange-funds-line"></i>+{{ item.point}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="record-list" v-else>
            <div class="record-item" v-for="item in list">
                <div class="record-type">
                    <div class="record-title">{{ item.msg}}</div>
                    <div class="record-value exp">
                        <b class="mr5">+{{ item.num}}</b>经验</div></div>
                <div class="record-detail">
                    <div class="record-desc">{{ item.type}}</div>
                    <div class="record-date">{{ item.creat_time }}</div></div>
            </div>
        </div>
    </div>
</div>`;

const user_setting_template = `<ul class="author-edit-list">
    <div class="em14 flex jc h200" v-if="status">
        <span class="ant-spin-dot ant-spin-dot-spin">
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
            <i class="ant-spin-dot-item"></i>
        </span>
    </div>
    <li v-else>
        <div class="edit-name">昵称</div>
        <div class="edit-item">
            <div class="edit-input" v-if="input =='nickname'">
                <input type="text" v-model="item.nickname" class="form-control">
                <p class="setting-des">中文、英文或数字</p>
                <div class="edit-button">
                    <button class="but c-yellow-2" @click="input =''">取消</button>
                    <button class="c-blue-2 but" @click="getuserinfo('nickname',item.nickname)">保存</button></div>
            </div>
            <div class="edit-value" v-else>
                <span>{{ item.nickname }}</span>
                <span class="user-edit-button" @click="input ='nickname'">
                    <i class="ri-edit-line"></i>编辑</span>
            </div>
        </div>
    </li>
    <li>
        <div class="edit-name">性别</div>
        <div class="edit-item">
            <div class="edit-input setting-sex" v-if="input =='sex'">
                <span>
                    <label>
                        <input v-model="item.sex" type="radio" value="0">男</label></span>
                <span>
                    <label>
                        <input v-model="item.sex" type="radio" value="1">女</label></span>
                <div class="edit-button">
                    <button class="but c-yellow-2" @click="input =''">取消</button>
                    <button class="c-blue-2 but" @click="getuserinfo('sex',item.sex)">保存</button></div>
            </div>
            <div class="edit-value" v-else>
                <span>{{ item.sex ==0 ? '男':'女' }}</span>
                <span class="user-edit-button" @click="input ='sex'">
                    <i class="ri-edit-line"></i>编辑</span>
            </div>
        </div>
    </li>
    <li>
        <div class="edit-name">一句话介绍自己</div>
        <div class="edit-item">
            <div class="edit-input" v-if="input =='motto'">
                <input type="text" v-model="item.motto" class="form-control" placeholder="一句话介绍自己">
                <div class="edit-button">
                    <button class="but c-yellow-2" @click="input =''">取消</button>
                    <button class="c-blue-2 but" @click="getuserinfo('motto',item.motto)">保存</button></div>
            </div>
            <div class="edit-value" v-else>
                <span>{{ item.motto ? item.motto:'这人很懒什么都没留下' }}</span>
                <span class="user-edit-button" @click="input ='motto'">
                    <i class="ri-edit-line"></i>编辑</span>
            </div>
        </div>
    </li>
    <li>
        <div class="edit-name">绑定手机</div>
        <div class="edit-item">
            <div class="edit-input" v-if="input =='phone'">
                <input type="text" v-model="item.phone" class="form-control" placeholder="手机号码">
                <div class="user-settings-code">
                    <input type="text" v-model="item.code" class="form-control" placeholder="验证码">
                    <button class="but c-blue-2" v-if="!countdown" @click="startCountdown">发送验证码</button>
                    <button class="but c-blue-2" v-else>剩余: {{ countdown }} 秒</button></div>
                <div class="edit-button">
                    <button class="but c-yellow-2" @click="input =''">取消</button>
                    <button class="c-blue-2 but" @click="getuserinfo('phone',item.phone)">保存</button></div>
            </div>
            <div class="edit-value" v-else>
                <span>{{ item.phone }}</span>
                <span class="user-edit-button" @click="input ='phone'">
                    <i class="ri-edit-line"></i>编辑</span>
            </div>
            <p class="setting-des">手机号码可用作登录</p></div>
    </li>
</ul>`;

const user_userpic_template = `<a class="avatar-set-link absolute hover-show-con flex jc xx" href="javascript:;">
    <i class="ri-image-add-fill"></i>
    <input class="userpic-model" type="file" @change="handleFileChange" accept="image/*" /></a>`;
    
const user_sign_template = `<a class="but c-blue ml10 pw-1em radius50" href="javascript:;" @click="getusersign()">
<i class="ri-calendar-check-line mr-2"></i>{{ sign ? sign : status }}</a>`;
                                
const user_order_template = `<div class="author-edit-page qh-radius">
    <div class="order-page">
        <div class="order-body">
            <div id="tabs" class="tabs">
                <ul class="tabs-nav box qh-radius" style="padding: 16px;">
                    <li :class="active == 2 ? 'active':''" @click="getlist(2)">全部</li>
                    <li :class="active == 1 ? 'active':''" @click="getlist(1)">已完成</li>
                    <li :class="active == 0 ? 'active':''" @click="getlist(0)">待支付</li></ul>
                <div class="tabs-content">
                    <div class="em14 flex jc h200" v-if="isstatus">
                        <span class="ant-spin-dot ant-spin-dot-spin">
                            <i class="ant-spin-dot-item"></i>
                            <i class="ant-spin-dot-item"></i>
                            <i class="ant-spin-dot-item"></i>
                            <i class="ant-spin-dot-item"></i>
                        </span>
                    </div>
                    <div class="order-list" v-for="item in list" v-else>
                        <div class="order-item">
                            <div class="store-info">
                                <div class="store-name">
                                    <i class="ri-store-2-line" style="font-size: 20px; margin-right: 4px;"></i>{{ item.subject }}</div>
                                <div class="order-status status-0" :style="item.status ?'color: #1a7af8;font-weight: 600;':''">{{ !item.status ? '待支付':'已支付' }}</div></div>
                            <div class="product-info">
                                <div class="product-image">
                                    <img :src="item.images" class="w-h"></div>
                                <div class="product-details">
                                    <div class="product-name">{{ item.msg }}</div>
                                    <div class="product-quantity"></div>
                                </div>
                                <div class="product-price">￥{{ item.money }}</div></div>
                            <div class="total-amount">实付款 ￥{{ item.money }}</div>
                            <div class="order-action">
                                <button @click="deletes(item.id)" class="delete-order but c-yellow-2">删除订单</button></div>
                            <div class="order-info">
                                <div class="order-number">
                                    <div class="label">订单编号</div>
                                    <div class="value">{{ item.order_sn }}</div></div>
                                <div class="order-date">
                                    <div class="label">订单时间</div>
                                    <div class="value">{{ item.addtime }}</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center;background: #FFF;border-radius: 4px;">
            <img v-if="list.length == 0 && isstatus == 0" style="width: 30%" src="/static/them/images/Nodata-bro.svg"></div>
    </div>
</div>`;

const messages_page_template = `<div class="message-box qh-im box">
    <div class="sub-panel">
        <div class="title">近期消息</div>
        <div class="chat-list">
            <div class="chat-list-wrapper">
                <div :class="active == item.id ? 'chat-item active' : 'chat-item'" v-for="item in list" @click="Getpage(item.type,item.send_from,item.id)">
                  <span v-if="item.isread" style="padding:4px" class="position-absolute top-0 start-100 translate-middle bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                  </span>
                    <div class="user-avatar">
                        <img :src="item.userpic" class="avatar-face w-h"></div>
                    <div class="user-info dm">
                        <div class="name-box">
                            <div class="user-name">
                                <div class="name">{{ item.nickname }}</div></div>
                            <div class="time">
                                <time class="qh-timeago">{{ item.time }}</time></div>
                        </div>
                        <div class="last-word">{{ item.content }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="qh-empty qh-radius box" v-if="pages.length === 0">
        <img src="/static/them/images/chat-rafiki.svg" class="empty-img" style="height: 50%"></div>
    <div class="main-panel">
        <div :style="{ height: text ? '75%' : '100%' }" v-html="pages"></div>
        <div class="send-box" v-if="text">
            <div class="input-box">
                <textarea v-model="content" placeholder="输入私信内容..." maxlength="500" class="form-control"></textarea>
            </div>
            <div class="send-btn">
                <button class="c-blue but qh_message" @click="submit()">
                    <i class="ri-quill-pen-line mr6"></i>发送</button>
            </div>
        </div>
    </div>
</div>`;

const write_page_template = `<main class="site-main box qh-radius">
    <div class="write-title">
        <textarea v-model="params.title" rows="1" placeholder="请在这里输入标题" class="write-textarea"></textarea>
    </div>
    <div id="editor-wrapper">
        <div id="toolbar-container">
            <!-- 工具栏 --></div>
        <div id="editor-container">
            <!-- 编辑器 --></div>
    </div>
    <div class="post-setting">
        <div class="setting-title">发布设置</div>
        <div class="mg-b">
            <div class="widget-title">增加文章封面</div>
            <div class="write-thumb qh-radius">
                <label class="w-h dashed" @click="thumb = true" v-if="!params.thumb">
                    <i class="ri-camera-line"></i>
                    <div>添加特色图</div></label>
                <div class="write-thumb qh-radius" v-else>
                    <img :src="params.thumb" class="w-h">
                    <div class="WriteCoverV2-buttonGroup">
                        <span @click="thumb = true">更换</span>
                        <span @click="params.thumb = ''">删除</span></div>
                </div>
                <write-thumb :show="thumb" @close="val=>thumb = val" @box="val=>params.thumb = val"></write-thumb>
            </div>
        </div>
        <div class="mg-b">
            <div class="widget-title">文章分类</div>
            <div class="write-select-box mg-b">
                <p>请选择文章分类</p>
                <n-tree-select :options="pids" label-field="catname" key-field="catid" children-field="children" placeholder="请选择上级栏目" clearable v-model:value="params.catid" @update:value="val=>params.catid = val" /></div>
            <div class="widget-title">文章标签</div>
            <div class="write-select-box">
                <p>标签能让更多小伙伴看到你的作品</p>
                <n-dynamic-tags v-model:value="params.tags" /></div>
        </div>
        <div class="mg-b">
            <div class="widget-title">内容权限</div>
            <div class="write-select-box">
                <p>如果您在文章中插入了隐藏内容，需要在此处设置查看权限，方可正常隐藏。</p>
                <n-select :options="options" /></div>
        </div>
    </div>
</main>
<div class="write-footer">
    <div class="wrapper">
        <div class="write-footer-info">
            <div class="left-info">
                <span class="tw-ml-4">共 {{ charCount }} 字</span></div>
            <div class="button-submit" style="width: 10%">
                <button class="publish" @click="submit()">提交发布</button></div>
        </div>
    </div>
</div>`;

const write_thumb_template = `<div v-if="show" :class="{ 'modal qh-modal fade': true, 'show': show }" :style="{ 'display': show ? 'block' : 'none' }">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="close">
                <i class="ri-close-fill" @click="$emit('close',false)"></i>
            </div>
            <div class="image-upload-box">
                <div class="container-top">
                    <span class="active">我的图片</span></div>
                <div class="container-content">
                    <div class="my-header pd16 box-body">
                        <div class="flex ac jsb">
                            <div class="relative">
                                <input class="form-control search-input" type="text" placeholder="搜索" name="search"></div>
                            <div class="flex0 flex ac">
                                <label style="margin: 0;">
                                    <input style="display: none;" multiple="multiple" type="file" @change="handleFileChange" accept="image/*">
                                    <a class="but c-blue">
                                        <i class="ri-upload-cloud-line"></i>上传</a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="images-list">
                        <div class="em14 flex jc h200" v-if="status" style="margin: auto;">
                            <span class="ant-spin-dot ant-spin-dot-spin">
                                <i class="ant-spin-dot-item"></i>
                                <i class="ant-spin-dot-item"></i>
                                <i class="ant-spin-dot-item"></i>
                                <i class="ant-spin-dot-item"></i>
                            </span>
                        </div>
                        <div :class="active == item.originname ? 'list-item active':'list-item'" v-for="item in list" @click="active = item.originname" v-else>
                            <div class="img">
                                <img :src="item.originname" class="w-h"></div>
                        </div>
                    </div>
                </div>
                <div class="insert-img-button">
                    <button @click="submit()">确认</button></div>
            </div>
        </div>
    </div>
</div>`;
