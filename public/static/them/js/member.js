setMinHeight();
/**
 * Getuserinfo 函数用于获取会员中心逻辑模块并渲染
 * @author zhaosong
 */
function Getuserinfo() {
	let info = document.querySelectorAll('.Userinfo');
	if (info.length > 0) {
		for (let i = 0; i < info.length; i++) {
			const dataParam = JSON.parse(info[i].getAttribute('data-info'));
			if (dataParam === null || typeof dataParam.key === 'undefined') {
				return false;
			}
			let key = dataParam.key;
			let query = '#userinfo-' + key;
			if (info[i].id != 'undefined') {
				const Userinfos = createApp({});
				Userinfos.component('Record_limit', {
					template: record_limit_template,
					setup() {
						const type = ref('');
						const status = ref(1);
						const Record = ref('');
						onMounted(() => {
							getRecord(0)
						});
						const getRecord = (types) => {
							status.value = 1;
							type.value = types
							axios.post('/member/api/record', {
									type: types
								})
								.then((res) => {
									if (res.data.status == 200) {
										Record.value = res.data.data;
										status.value = 0;
									}
								});
						}
						return {
							type,
							status,
							getRecord,
							Record
						}
					},
				});
				Userinfos.component('growth_list', {
					template: growth_list_template,
					setup() {
						const type = ref(0);
						const status = ref(1);
						const list = ref('');
						onMounted(() => {
							getgrowth(0)
						});
						const getgrowth = (types) => {
							status.value = 1;
							type.value = types
							axios.post('/member/api/growth', {
									type: types
								})
								.then((res) => {
									if (res.data.status == 200) {
										list.value = res.data.data;
										status.value = 0;
									}
								});
						}
						return {
							type,
							status,
							list,
							getgrowth
						}
					}
				});

				Userinfos.component('user_setting', {
					template: user_setting_template,
					setup() {
						const item = ref('');
						const input = ref('');
						const status = ref(1);

						const countdown = ref(0);
						let intervalId = null;

						onMounted(() => {
							getuserinfo()
						});
						const getuserinfo = (field, value) => {
							if (field == 'phone') {
								let cookieValue = getCookieValue('phone_code');
								if (cookieValue !== item.value.code) {
									message.error('验证码错误');
									return;
								}
							}
							status.value = 1;
							axios.post('/member/api/setting', {
									field: field,
									value: value
								})
								.then((res) => {
									if (res.data.status == 200) {
										item.value = res.data.data;
										status.value = 0;
										input.value = '';
									}
								});
						}
						const startCountdown = () => {
							if (item.value.phone.length !== 11) {
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
									phone: item.value.phone,
									code: item.value.code
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
							getuserinfo,
							status,
							item,
							input,
							countdown,
							startCountdown
						}
					}
				});
				Userinfos.component('user_userpic', {
					template: user_userpic_template,
					setup() {
						const imageUrl = ref(null);
						const handleFileChange = async (event) => {
							const file = event.target.files[0];
							if (file) {
								const formData = new FormData();
								formData.append('image', file);

								try {
									const response = await axios.post('/member/api/userpic', formData, {
										headers: {
											'Content-Type': 'multipart/form-data',
										},
									});
									imageUrl.value = response.data.imageUrl;
									if (response.data.status == 200) {
										window.location.reload();
									}
								} catch (error) {
									message.error('Image upload failed:', error);
								}
							}
						};

						return {
							imageUrl,
							handleFileChange,
						};
					},

				});
				Userinfos.component('user_sign', {
					template: user_sign_template,
					props: {
						status: {
							type: String,
							default: '签到'
						}
					},
					setup(props) {
						const sign = ref('');
						const getusersign = () => {
							sign.value = '正在签到...';
							axios.post('/member/api/sign')
								.then((res) => {
									if (res.data.status == 200) {
										message.success(res.data.msg);
										sign.value = '已签到';
									} else {
										message.error(res.data.msg);
										sign.value = '已签到';
									}
								});
						}
						return {
							sign,
							status: props.status,
							getusersign
						}
					}
				});
				Userinfos.component('user_order', {
					template: user_order_template,
					setup() {
						const active = ref(2);
						const isstatus = ref(1);
						const list = ref([]);
						onMounted(() => {
							getlist(active.value)
						});
						const getlist = (status) => {
							active.value = status
							isstatus.value = 1
							axios.post('/member/api/order_list', {
									status: status
								})
								.then((res) => {
									if (res.data.status == 200) {
										isstatus.value = 0
										list.value = res.data.data
									}

								});
						}
						const deletes = (id) => {
							if (window.confirm('确定要删除该订单吗？')) {
								axios.post('/member/api/order_list', {
										id: id
									})
									.then((res) => {
										if (res.data.status == 200) {
											message.success(res.data.msg, {
												onClose: () => {
													getlist(active.value)
												}
											});
										}
									});
							}
						}
						return {
							active,
							list,
							getlist,
							deletes,
							isstatus
						}
					}
				});

				Userinfos.mount(query)
			}
		}
	}


}

Getuserinfo()

