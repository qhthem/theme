/**
 * Getwrite 函数用于获取写作模块并渲染
 * @author zhaosong
 */
function Getwrite() {
	let info = document.querySelectorAll('.qh-write');
	if (info.length > 0) {
		for (let i = 0; i < info.length; i++) {
			const dataParam = JSON.parse(info[i].getAttribute('data-write'));
			if (dataParam === null || typeof dataParam.key === 'undefined') {
				return false;
			}
			let key = dataParam.key;
			let query = '#write-' + key;
			if (info[i].id != 'undefined') {
				const {
					createEditor,
					createToolbar
				} = window.wangEditor
				const Writes = createApp({});
				const {
					createDiscreteApi
				} = naive
				Writes.component('write-page', {
					template: write_page_template,
					setup() {
						const pids = ref([]);
						const tags = ref(['教师', '程序员'])
						const options = ref([]);
						const thumb = ref(false);
						const params = ref({
							title: '',
							content: '',
							catid: '',
							tags: [],
							thumb: ''
						});
						onMounted(() => {
							nextTick(() => {
								initializeEditor();
							});
							getcatid()
						});
						const initializeEditor = () => {
							const editorConfig = {
								placeholder: '请输入你的内容...',
								onChange: (editor) => {
									const html = editor.getHtml();
									params.value.content = html
								},
								MENU_CONF: {
									uploadImage: {
										server: `/index/api/upload`,
										timeout: 5 * 1000,
										fieldName: 'file',
										metaWithUrl: true,
										headers: {
											Accept: 'text/x-json'
										},
										maxFileSize: 10 * 1024 * 1024,
										base64LimitSize: 5 * 1024,
									},
								},
							};

							const editor = createEditor({
								selector: '#editor-container',
								config: editorConfig,
								mode: 'default',
							});

							const toolbarConfig = {};

							const toolbar = createToolbar({
								editor: editor,
								selector: '#toolbar-container',
								config: toolbarConfig,
								mode: 'default',
							});

							editor.setHtml(params.value.content);
						}
						const getcatid = () => {
							axios.post('/member/api/getcatid')
								.then((res) => {
									if (res.data.status == 200) {
										pids.value = res.data.data;
									}
								});
						}
						const charCount = computed(() => {
							const chineseChars = params.value.content.match(
								/[\u4e00-\u9fa5]/g); // 使用正则表达式匹配汉字字符
							return chineseChars ? chineseChars.length : 0; // 计算汉字字符数
						});
						const submit = () => {
							if (params.value.title == '') {
								message.error('标题不能为空！');
								return false
							}
							if (params.value.content == '') {
								message.error('内容不能为空！');
								return false
							}
							axios.post('/member/api/add', params.value)
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
							pids,
							tags,
							getcatid,
							options,
							params,
							submit,
							thumb,
							charCount,
						}
					},
				});
				Writes.component('write-thumb', {
					template: write_thumb_template,
					props: {
						show: {
							default: false
						},
					},
					setup(props, {
						emit
					}) {
						const list = ref([]);
						const active = ref('');
						const status = ref(1);
						watch(() => props.show, (newValue) => {
							if (newValue) {
								getlist();
							}
						});
						onMounted(() => {

						});

						const getlist = () => {
							status.value = 1;
							axios.post('/member/api/image_box', {
									limit: 6
								})
								.then((res) => {
									if (res.data.status == 200) {
										list.value = res.data.data.data
										status.value = 0;
									}
								});
						}
						const handleFileChange = async (event) => {
							const file = event.target.files[0];
							if (file) {
								const formData = new FormData();
								formData.append('image', file);

								try {
									const response = await axios.post('/member/api/image_upload',
										formData, {
											headers: {
												'Content-Type': 'multipart/form-data',
											},
										});

									if (response.data.status == 200) {
										message.success(res.data.msg, {
											onClose: () => {
												getlist();
											}
										});
									}
								} catch (error) {
									getlist();
								}
							}
						};
						const submit = () => {
							if (active.value == '') {
								message.error('请选择图片！');
								return false
							} else {
								emit('box', active.value)
								active.value == ''
								emit('close', false)
							}
						};
						return {
							list,
							active,
							submit,
							handleFileChange,
							status
						}
					}

				});
				Writes.use(naive)
				Writes.mount(query)
			}
		}
	}
}

Getwrite()