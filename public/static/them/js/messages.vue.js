/**
 * Getmessages 函数用于获取并处理消息
 * @author zhaosong
 */
function Getmessages() {
    let info = document.querySelectorAll('.qh-messages');
    if (info.length > 0) {
        for (let i = 0; i< info.length; i++) {
            const dataParam = JSON.parse(info[i].getAttribute('data-info'));
            if (dataParam === null || typeof dataParam.key === 'undefined') {
                return false;
            }
            let key = dataParam.key;
            let query = '#messages-' + key;
            if (info[i].id != 'undefined') {
                const mess = createApp({});
                mess.component('messages-page', {
                    template: messages_page_template,
                    setup() {
                        const list = ref([]);
                        const pages = ref('');
                        const from = ref('')
                        const text = ref(false);
                        const active = ref('');
                        const content = ref('');
                        onMounted(() => {
                            Getmessages_list()
                        });

                        const Getmessages_list = () => {
                            axios.post('/member/messages/message_list')
                                .then((res) => {
                                    if (res.data.status == 200) {
                                        list.value = res.data.data;
                                    }
                                });
                        }
                        const Getpage = (type, send_from, id) => {
                            active.value = id
                            from.value = send_from
                            let datas = { type: type, send_from: send_from ? send_from : false }
                            axios.post('/member/messages/message_page', datas)
                                .then((res) => {
                                    if (res.data.status == 200) {
                                        pages.value = res.data.data;
                                        send_from == false ? text.value = false : text.value = true

                                    }
                                });
                        }
                        const submit = () => {
                            if (content.value == '') {
                                message.error('内容不能为空！');
                                return false
                            }
                            let params = {
                                content: content.value,
                                send_to: from.value
                            }
                            axios.post('/member/messages/message_add', params)
                                .then((res) => {
                                    if (res.data.status == 200) {
                                        message.success(res.data.msg);
                                        Getpage('', from.value, '')
                                        content.value = ''
                                    } else {
                                        message.error(res.data.msg);
                                    }
                                });
                        }
                        return {
                            Getmessages_list,
                            list,
                            Getpage,
                            pages,
                            active,
                            text,
                            content,
                            from,
                            submit

                        }
                    },
                });
                mess.mount(query)
            }
        }
    }
}
Getmessages()