const {
	ref,
	toRefs,
	onMounted,
	createApp,
	nextTick,
	reactive,
	computed,
	watch,
	emit,
	onUnmounted
} = Vue
//懒加载
var lazyLoadInstance = new LazyLoad({
	elements_selector: ".lazyload",
	threshold: 0,
});

//加载瀑布流
function qhPackeryLoad() {
	var grid = document.querySelectorAll('.qh-waterfall:not(.circle-moment-list)')
	if (grid.length > 0) {
		for (let index = 0; index < grid.length; index++) {
			let pack = new Packery(grid[index])
		}
	}
}

function getCookieValue(cookieName) {
	// 获取所有cookie字符串
	var allCookies = document.cookie;
	// 将cookie字符串分割成数组
	var cookiesArray = allCookies.split(';');
	// 遍历数组，找到与指定名称匹配的cookie值
	for (var i = 0; i < cookiesArray.length; i++) {
		var cookie = cookiesArray[i].trim();
		// 检查cookie名称是否匹配
		if (cookie.substring(0, cookieName.length + 1) == (cookieName + '=')) {
			// 返回cookie值
			return cookie.substring(cookieName.length + 1);
		}
	}
	// 如果没有找到匹配的cookie，返回null
	return null;
}

qhPackeryLoad();

// 下滑时侧边栏固定浮动
$(document).ready(function() {
	var $sidebar = $('.widget-box-s');

	// 如果没有找到 .widget-box-s 元素，则不执行函数
	if ($sidebar.length === 0) {
		return;
	}

	var sidebarTop = $sidebar.offset().top;

	$(window).scroll(function() {
		var scrollTop = $(window).scrollTop();

		if (scrollTop > sidebarTop) {
			$sidebar.css({
				position: 'fixed',
				width: '285px',
				top: '70px'
			});
		} else {
			$sidebar.css({
				position: 'relative',
				top: 'auto'
			});
		}
	});
});


function setMinHeight() {
	var b = $("body");
	var w = $(window).width(),
		h = $(window).height();
	$(function() {
		if (hh = document.documentElement.clientHeight,
			hh > document.body.clientHeight || hh == document.body.clientHeight) {
			var t = hh - $("footer").outerHeight() - $("header").outerHeight() - 20;
			$("#app").animate({
				"min-height": t
			});
		}
	});
}

$("body").on("click", ".btn,.qh-panel .panel-item i ,.but,button", function(t) {
	var e = $(this);
	if (!e.hasClass("nowave")) {
		var a;
		e.css({
			overflow: "hidden",
			position: "relative"
		});
		var i = ~~e.outerWidth(),
			n = ~~e.outerHeight();
		a = i < n ? n : i;
		var o = e.css("color") || "rgba(200, 200, 200)",
			r = $("<div></div>").css({
				display: "block",
				background: o,
				"border-radius": "50%",
				position: " absolute",
				"-webkit-transform": "scale(0)",
				transform: "scale(0)",
				opacity: ".3",
				"-webkit-transition": "all 1.5s cubic-bezier(0.22, 0.61, 0.36, 1)",
				transition: "all 1.5s cubic-bezier(0.22, 0.61, 0.36, 1)",
				"z-index": "1",
				overflow: "hidden",
				"pointer-events": "none"
			});
		e.append(r),
			r.css({
				width: 2 * a + "px",
				height: 2 * a + "px",
				top: t.pageY - e.offset().top - a + "px",
				left: t.pageX - e.offset().left - a + "px",
				transform: "scale(1)",
				"-webkit-transform": "scale(1)",
				opacity: 0
			}),
			setTimeout(function() {
				r.remove()
			}, 2e3)
	}
})

function initFancybox() {
  var $article_img = $('.article-content img');
  if ($article_img.length === 0) {
    return false;
  }
  $article_img.each(function() {
    $(this).attr('data-fancybox', 'gallery');
  });

  // 立即绑定Fancybox
  Fancybox.bind('[data-fancybox="gallery"]', {
    // ...
  });
}

$(document).ready(initFancybox);


/**
 * indexPostModules 函数用于获取内容列表并渲染
 * @author zhaosong
 */
function indexPostModules() {
	let list = document.querySelectorAll('.post-list');
	if (list.length > 0) {
		for (let i = 0; i < list.length; i++) {

			const dataParam = JSON.parse(list[i].getAttribute('data-param'));
			if (dataParam === null || typeof dataParam.key === 'undefined') {
				return false;
			}
			let key = dataParam.key;
			let query = '#post-item-' + key;

			if (list[i].id != 'undefined') {
				const app = createApp({
					setup() {
						const key = ref(0);
						const paged = ref(1);
						const locked = ref(false);
						const noMore = ref(false);
						const catid = ref(dataParam.catid);
						const total = ref(0);
						const limit = dataParam.limit;

						onMounted(() => {
							getList(0)
							lazyLoadInstance.update()
							qhPackeryLoad()
						});

						const getList = (catids, type, page, modelid) => {
							if (noMore.value && type == 'more') {
								return;
							} else {
								noMore.value = false;
							}
							catid.value = catids
							if (locked.value == true) return;
							locked.value = true;

							if (type != 'more') {
								paged.value = 1;
							} else {
								paged.value = paged.value + 1;
							}

							let data = {
								modelid: dataParam.modelid ? dataParam.modelid : modelid,
								style: dataParam.style,
								catid: catids ? catids : dataParam.catid,
								limit: dataParam.limit,
								page: page ? page : paged.value,
								userid: dataParam.userid,
								keyword: dataParam.keyword
							};

							if (type == 'change') {
								data['order'] = 'RAND()';
							}

							let box = document.querySelector(query + ' .qh-grid');
							axios
								.post('/index/api/content', data)
								.then((res) => {
									if (res.status == 200) {

										total.value = res.data.total
										if (res.data.data.length == 0) {
											noMore.value = true
										}

										if (type != 'more') {
											box.innerHTML = res.data.data;
										} else {
											box.insertAdjacentHTML('beforeend', res.data.data);
										}

									} else {
										box.innerHTML = res.data.data;
									}

									locked.value = false;
									qhPackeryLoad();
									nextTick(() => {
										lazyLoadInstance.update();
										setMinHeight();
									});
								});
						};

						const loadMore = () => {
							getList(catid.value, 'more');
						};

						const exchange = () => {
							getList(catid.value, 'change');
						};

						return {
							key,
							paged,
							locked,
							noMore,
							catid,
							getList,
							loadMore,
							exchange,
							total,
							limit
						};
					}
				});

				app.component('pagination', {
					template: pagination_template,
					props: {
						totalItems: {
							type: Number,
							required: true
						},
						itemsPerPage: {
							type: Number,
							required: true
						}
					},
					setup(props, {
						emit
					}) {
						const currentPage = ref(1);

						const totalPages = computed(() => {
							return Math.ceil(props.totalItems / props.itemsPerPage);
						});

						const changePage = (pageNumber) => {
							currentPage.value = pageNumber;
							emit('page', pageNumber);
						};

						const previousPage = () => {
							if (currentPage.value > 1) {
								currentPage.value--;
								emit('page', currentPage.value);
							}
						};

						const nextPage = () => {
							if (currentPage.value < totalPages.value) {
								currentPage.value++;
								emit('page', currentPage.value);
							}
						};

						return {
							currentPage,
							totalPages,
							changePage,
							previousPage,
							nextPage
						};
					}
				})

				app.mount(query);
			}
		}
	}
}

indexPostModules()

/**
 * createModal 函数用各种模态框模块并渲染
 * @author zhaosong
 */
function createModal() {
	let Modal = document.querySelectorAll('.Modal');
	if (Modal.length > 0) {
		for (let i = 0; i < Modal.length; i++) {

			const dataParam = JSON.parse(Modal[i].getAttribute('data-modal'));
			if (dataParam === null || typeof dataParam.key === 'undefined') {
				return false;
			}
			let key = dataParam.key;
			let query = '#Modal-' + key;

			if (Modal[i].id != 'undefined') {
				const Modals = createApp({
					setup() {
						const showModal = ref(false);
						const NameModal = ref('');
						onMounted(() => {

						});
						const openModal = (names) => {
							showModal.value = true;
							NameModal.value = names;
						}

						return {
							showModal,
							NameModal,
							openModal
						};
					}
				});
				Modals.component('login', {
					template: login_template,
					props: {
						show: {
							default: false
						},
					},
					setup(props) {
						const boxtype = ref(0);
						const username = ref('');
						const password = ref('');
						const email = ref('');

						const login_subimt = () => {
							let params = {
								username: username.value,
								password: password.value
							}
							axios.post("/member/index/login", params).then((res) => {
								if (res.data.status == 200) {
									message.success(res.data.msg, {
										onClose: () => {
											window.location.reload();
										}
									});
								} else {
									message.error(res.data.msg);
								}
							});
						}
						const register_subimt = () => {
							let params = {
								username: username.value,
								email: email.value,
								password: password.value
							}
							message.error('当前关闭注册，请使用QQ进行注册！');
							return false

							if (!email.value) {
								message.error('请输入邮箱！');
								return false
							}
							axios.post("/member/index/register", params).then((res) => {
								if (res.data.status == 200) {
									message.success(res.data.msg, {
										onClose: () => {
											window.location.reload();
										}
									});
								} else {
									message.error(res.data.msg);
								}
							});
						}

						return {
							username,
							password,
							email,
							login_subimt,
							register_subimt,
							boxtype,

						}
					}
				})
				
				Modals.component('Model', {
					template: model_template,
					props: {
						show: {
							default: false
						},
						name: {
							default: ''
						},
						params: {
							default: {}
						}
					},
					setup(props) {
						return {

						}
					}
				});
                
				Modals.component('R_moeny', {
					template: moeny_template,
					setup() {
						const paytype = ref('');
						const moeny = ref('');
						const number = ref('');
						const status = ref(1);

						const qrcode = ref('');
						const order_sn = ref('');
						const totalTime = 5 * 60; // 五分钟，单位为秒
						const remainingTime = ref(totalTime); // 剩余时间，单位为秒
						let intervalId;

						onMounted(() => {
							getdata('R_moeny')
						});
						onUnmounted(() => {
							clearInterval(intervalId);
						});

						const countdown = computed(() => {
							const minutes = Math.floor(remainingTime.value / 60);
							const seconds = remainingTime.value % 60;
							return `${minutes}:${seconds.toString().padStart(2, '0')}`;
						});

						const startCountdown = () => {
							intervalId = setInterval(() => {
								if (remainingTime.value > 0) {
									order_status()
									remainingTime.value -= 1;
								} else {
									clearInterval(intervalId);
									message.error('支付超时！');
								}
							}, 1000);
						};
						const buyamount = () => {
							if (paytype.value == '') {
								message.error('请选择付款方式！');
								return false
							}
							if (number.value == '') {
								message.error('请选择套餐类型！');
								return false
							}
							let data = {
								value: number.value,
								paytype: paytype.value,
							}
							axios.post('/member/api/buy_amount', data)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												qrcode.value = res.data.qrcode
												order_sn.value = res.data.order_sn
												startCountdown();
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						const order_status = () => {
							axios.post('/member/api/order_status', {
									out_trade_no: order_sn.value
								})
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												clearInterval(intervalId);
												window.location.reload();
											}
										});
									}
								});
						}
						const getdata = (type) => {
							axios.post('/member/api/Recharge', {
									type: type
								})
								.then((res) => {
									if (res.data.status == 200) {
										moeny.value = res.data.data
										status.value = 0
									}
								});
						}
						return {
							paytype,
							moeny,
							number,
							status,
							getdata,
							buyamount,
							countdown,
							qrcode,
							order_sn,
							order_status

						}
					}
				});
				Modals.component('R_point', {
					template: point_template,
					setup() {
						const paytype = ref('');
						const label = ref('');
						const moeny = ref('');
						const number = ref('');
						const status = ref(1);

						const qrcode = ref('');
						const order_sn = ref('');
						const totalTime = 5 * 60; // 五分钟，单位为秒
						const remainingTime = ref(totalTime); // 剩余时间，单位为秒
						let intervalId;

						onMounted(() => {
							getdata('R_point')
						});
						onUnmounted(() => {
							clearInterval(intervalId);
						});

						const countdown = computed(() => {
							const minutes = Math.floor(remainingTime.value / 60);
							const seconds = remainingTime.value % 60;
							return `${minutes}:${seconds.toString().padStart(2, '0')}`;
						});

						const startCountdown = () => {
							intervalId = setInterval(() => {
								if (remainingTime.value > 0) {
									order_status()
									remainingTime.value -= 1;
								} else {
									clearInterval(intervalId);
									message.error('支付超时！');
								}
							}, 1000);
						};
						const buypoint = () => {
							if (paytype.value == '') {
								message.error('请选择付款方式！');
								return false
							}
							if (number.value == '') {
								message.error('请选择套餐类型！');
								return false
							}
							let data = {
								value: number.value,
								paytype: paytype.value,
								point: label.value
							}
							axios.post('/member/api/buy_point', data)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												if (paytype.value == 'alipay') {
													qrcode.value = res.data.qrcode
													order_sn.value = res.data.order_sn
													startCountdown();
												} else {
													window.location.reload();
												}
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						const order_status = () => {
							axios.post('/member/api/order_status', {
									out_trade_no: order_sn.value
								})
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												clearInterval(intervalId);
												window.location.reload();
											}
										});
									}
								});
						}
						const getdata = (type) => {
							axios.post('/member/api/Recharge', {
									type: type
								})
								.then((res) => {
									if (res.data.status == 200) {
										moeny.value = res.data.data
										status.value = 0
									}
								});
						}
						return {
							paytype,
							moeny,
							number,
							status,
							getdata,
							buypoint,
							label,
							countdown,
							qrcode,
							order_sn,
							order_status

						}
					}
				});
				Modals.component('transfer_point', {
					template: transfer_point_template,
					setup() {
						const params = ref({
							type: 'password',
							password: '',
							password2: ''
						});
						const inputValue = ref('');
						let lastTriggeredLength = 0;
						const user_list = ref([]);
						const userinfo = ref([]);
						const status = ref('搜索用户进行转账');

						onMounted(() => {

						});
						const search_list = (event) => {
							status.value = '正在搜索，请稍后...'
							inputValue.value = event.target.value;
							if (inputValue.value.length >= 2 && inputValue.value.length !==
								lastTriggeredLength) {
								axios.post('/member/api/search_user', {
										s: inputValue.value
									})
									.then((res) => {
										if (res.data.status == 200) {
											user_list.value = res.data.data
											status.value = '请选择用户进行转账'
										}
									});
							}
						}
						const Transfer = () => {
							axios.post('/member/api/transfer', userinfo.value)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												window.location.reload();
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						return {
							search_list,
							inputValue,
							user_list,
							params,
							status,
							userinfo,
							Transfer
						}
					}

				});
				Modals.component('account_password', {
					template: account_password_template,

					setup() {
						const params = ref({
							type: 'password',
							password: '',
							password2: ''
						});
						const submit_password = () => {
							axios.post('/member/api/account', params.value)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												window.location.reload();
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						return {
							submit_password,
							params
						}
					}

				});

				Modals.component('account_email', {
					template: account_email_template,
					setup() {
						const params = ref({
							type: 'email',
							email: '',
							code: ''
						});
						const countdown = ref(0);
						let intervalId = null;

						const submit_email = () => {
							axios.post('/member/api/account', params.value)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												window.location.href = '/member';
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						const startCountdown = () => {
							if (params.value.email == '') {
								message.error('请输入邮箱！');
								return;
							}
							if (intervalId) return; // 防止重复点击
							countdown.value = 60;
							intervalId = setInterval(() => {
								countdown.value--;
								if (countdown.value <= 0) {
									clearInterval(intervalId);
									intervalId = null;
								}
							}, 1000);

							// 发送请求获取验证码
							axios.post('/member/api/send_code', {
									type: 'email',
									email: params.value.email
								})
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg);
									} else {
										message.error(res.data.msg);
									}
								});
						}

						return {
							submit_email,
							params,
							countdown,
							startCountdown
						}
					}
				});
				Modals.component('account_phone', {
					template: account_phone_template,
					setup() {
						const params = ref({
							type: 'phone',
							phone: '',
							code: ''
						});
						const countdown = ref(0);
						let intervalId = null;

						const submit_phone = () => {
							axios.post('/member/api/account', params.value)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												window.location.reload();
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						const startCountdown = () => {
							if (params.value.phone.length !== 11) {
								message.error('手机号格式错误');
								return;
							}
							if (intervalId) return; // 防止重复点击
							countdown.value = 60;
							intervalId = setInterval(() => {
								countdown.value--;
								if (countdown.value <= 0) {
									clearInterval(intervalId);
									intervalId = null;
								}
							}, 1000);
							// 发送请求获取验证码
							axios.post('/member/api/send_code', {
									type: 'phone',
									phone: params.value.phone
								})
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg);
									} else {
										message.error(res.data.msg);
									}
								});
						}

						return {
							submit_phone,
							params,
							countdown,
							startCountdown
						}
					}
				});
				Modals.component('user_follow', {
					template: user_follow_template,
					props: {
						clask: {
							default: ''
						},
						userid: '',
						status: 0
					},
					setup(props) {
						const status = Vue.ref(props.status);

						const submit_follow = (userid) => {
							axios.post('/member/api/follow', {
									followid: userid
								})
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg);
										status.value = res.data.follow;
									} else {
										message.error(res.data.msg);
									}
								});
						}
						return {
							submit_follow,
							status
						}
					}
				});

				Modals.component('user_msg', {
					template: user_msg_template,
					props: {
						params: {
							default: {}
						}
					},
					setup(props) {
						const content = ref('');
						const submit_msg = () => {
							axios.post('/member/messages/message_add', {
									send_to: props.params.userid,
									content: content.value
								})
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												window.location.reload();
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						return {
							content,
							submit_msg
						}
					}
				});
				Modals.component('side', {
					template: side_template,
					props: {
						params: {
							default: {}
						}
					},
					setup(props) {
						const active = ref("");
						const likeCount = Vue.ref(props.params.like);
						const favoriteCount = Vue.ref(props.params.favorite);

						const submit = (type) => {
							let data = {
								id: props.params.id,
								modelid: props.params.modelid,
								catid: props.params.catid,
								type: type,
							};
							axios
								.post("/index/api/like_favorite", data)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg);
										active.value = res.data.active;

										if (type === "likes") {
											likeCount.value = res.data.like;
										} else if (type === "favorite") {
											favoriteCount.value = res.data.favorite;
										}
									} else if (res.data.status == 100) {
										message.success(res.data.msg);
										active.value = res.data.active;

										if (type === "likes") {
											likeCount.value = res.data.like;
										} else if (type === "favorite") {
											favoriteCount.value = res.data.favorite;
										}
									}
								});
						};

						return {
							submit,
							active,
							likeCount,
							favoriteCount,
						};
					},

				});

				let replyCommentId = Vue.ref(null);
				let editor = Vue.ref(true);
				Modals.component('comment_post', {
					template: comment_post_template,
					props: {
						params: {
							default: {}
						}
					},
					setup(props, {
						emit
					}) {
						const {
							params
						} = toRefs(props);
						
						onMounted(() => {

						});
						const close = () => {
							replyCommentId.value = null;
							params.value.replyid = null;
							params.value.textarea = null;
							params.value.parentid = null;
							editor.value = true
						}
						const submit = () => {
						    let userid = params.value.userid ? params.value.userid :params.value.replyid
						    let parentid = params.value.ids ? params.value.ids :params.value.id
							let data = {
								modelid: params.value.modelid,
								replyid: userid,
								id: params.value.id,
								aid:  params.value.sid ?  params.value.sid :params.value.aid,
								textarea: params.value.textarea,
								parentid: parentid
							}
							axios.post('/index/api/comment', data)
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												window.location.reload();
											}
										});
									} else {
										message.error(res.data.msg);
									}
								});
						}
						return {
							submit,
							replyCommentId,
							close,
							editor
						}
					}
				});


				Modals.component('comment', {
					template: comment_template,
					props: {
						params: {
							type: Object,
							default: () => ({})
						},
					},
					setup(props) {
					    
						const {
							params: propsParams
						} = toRefs(props);
						const params = Vue.ref(propsParams);

						const reply = (userid,aid, id) => {
						    params.value.replyid = userid;
							params.value.aid = aid;
							params.value.parentid = id;
							
						    if (replyCommentId.value === id) {
							    replyCommentId.value = null;
							} else {
								editor.value = id;
								replyCommentId.value = id;
							}
						}
						return {
							reply,
							editor,
							replyCommentId
						}
					}
				});

				Modals.component('comment_list', {
					template: comment_list_template,
					props: {
						params: {
							default: {}
						}
					},
					setup(props) {
						const {
							params: propsParams
						} = toRefs(props);
						const params = Vue.ref(propsParams);
						const list = ref([]);
						const active = ref('');

						onMounted(() => {
							getlist('creat_time DESC')
						});
						const getlist = (order) => {
							active.value = order
							let Arraydata = Object.assign({
								order: active.value
							}, params.value);
							axios.post('/index/api/comment_list', Arraydata)
								.then((res) => {
									if (res.data.status == 200) {
										list.value = res.data.data.data
									}
								});
						}

						const reply = (userid, aid,id) => {
							if (replyCommentId.value === id) {
								replyCommentId.value = null;
							} else {
								params.value.replyid = userid;
								editor.value = false;
								replyCommentId.value = id;
							    params.value.ids = id;
							}
						}

						return {
							getlist,
							list,
							reply,
							editor,
							replyCommentId,
							active,
						}
					}
				});
				Modals.mount(query);
			}
		}
	}
}

createModal()