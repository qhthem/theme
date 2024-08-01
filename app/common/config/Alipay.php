<?php
// +----------------------------------------------------------------------
// | 支付宝支付设置
// +----------------------------------------------------------------------

return [
    //签名方式,默认为RSA2(RSA2048)
	'sign_type' => "RSA2",

	//支付宝公钥
	'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2tANBSBi8XppIU2FE6NNiHjMhNQPXoBQ9Pu7fnskRv/DvNYJeg7B7vLK4Gy29uYz7TK6BptaGq1DBNpiw8lUCEiwmwO/K71gIc1T9muthREsFgNMZaBZnZe/alsL0DT4M0uTnKWCqYEGpYxc8cQqWbGEAHrfhGgxNGP1Z1n0xaCX5PyWBv5jspcu4KyN9F6ydvHijQZpMAmnU+kH6zNddT/wXgDL2Nr4fNN4LxHq+BvesOQ3ufdKXx08f6qeKQR92EQJY3MDlwG9Xh3ks/QiBTACClp/+akZEd5mRuixaubXlMQFV9uFUs/eggTtjg4h9VaC+/ZMs36dloY47s1+OwIDAQAB",

	//商户私钥
	'merchant_private_key' => "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCueV6KNfYItcRYTQreA9vEBTempigcIgzPboeaXsbk361NUR2FnheOKL833NoBXM8edWuT5Sh5LAHIbvaV/FHKJE16QU4egayGbWfssjtWRfem8/KfGlEmlFxZe07R7FD9Bvma8jdyQF5+3kJrQwvfYPMa4ZmK0F5hPstgPojOfV50n7BPKuNaysxr4bYYmZ5zzVm7WPtwjWHfL+GNVZgcQuXa/KRM6C8zpRBW91WzCo1WNQfbDNfi1SB1Xkv2PR6/zTK/vN58qlertHjKli7CavUsDzyNKJU+SXzsqpa3EXXsMbuct+6wKAWPG+Nf0SS0q0iZwTvqy1GrUGRSablVAgMBAAECggEAIzSKRwksZAWJYOfq0XGl3p/O4umUHlkgjJqY8iU9rOEVbwx3EIcd6al/LB58PtojUNoQov8Sc6DVm0QIrasENe2tVIXo5W71CQr6dWBhaL8lgaKnykLdLROyrrua4/DzRpspsoI9fehPKPpgHJSiyKgfZcUv3jq9UJIDJ6B2qnN7midKlOJ/NPLwSDCiO+MnmjVN7T1Gq14qtCzkvaw9Gv82KogjURGE4py0NNtCTIDYCjj41OoC5Sqqpd0lmlxbOh7PtMoN13bTqifVS0dNRy/9pyWPs0iay6QokxyumFfI5wwSi5as+H2p2dUbKgnoAJxQ5czzmPeNZCUaS0JK4QKBgQDxO8W78CrwIIq5oH/+w5qRi6j/wMRUe+Zx0gdEQ0XfSGYbmQsuflV3V4j4FwtGfCVzTG56IvshrlXXfwsXTWlsCin23jqOKuxkR81oePgp5la6IfSVcB6dedsb9GWLyG1inq9XEjnKuXxOVnhSecrtTi2528ew3aOZFa642Qt3ewKBgQC5J3MvJA0I64jqd7v1WOso1Z4Tnl7900Lt1n9S5uz6Mo8tftcE4iXiXnq+PXRcf8cftYG7clKbHmXj0mYwBd2oDZB1k+KXh7ODjh1HZGwj3Of/vbDmIQ9FRmMlg40GMFZOfFawR2kRPYzZ6/+d7JXlbOlRX2QRDd5YLw8xbflRbwKBgQCc7T5HcQftp6227bL1/zbo4YpxvWu6bdNOTcvOW1jz1HwgDeIZP7PHa9k9UnhSROZz58+igmkN0wQiqJGFeiVPVBlSb+R1oXSgE5mLEc6WKzJV6Urbf9Fare+cctMwKJUH56S2prOTrjAwIc4qEPKyhv31/wQbiUMojdNYilEMSwKBgAzOerwW/UPRrs2w7HJC7TkM1Xs76AwccbOrs3DTgi6oEpkt/cdbsQ0gRdMkwwim2eoKjuedN7N8/cUtR14o+bTnA0fuq6yZeUQvLz1B5+zvGajpwPOZ88lx3ee5WqbD7yRMm15kAhNsM3LbTPnqZE/TIaw8MdqwH+Hvl1hu5+DHAoGANgVqMGmw7M0wT0y20Bx26n22YXl8E5NwQKixYcJBag4RuqilksLf1wukZnqK+fDov7TIjEGVXGnnB4L/60N90Vj99xweaUuG6H4ZJL9P1Xh9pY/N/tCMQOTdfZItSCKBZYpzlemHW45u6pAZnJAvCGnpBokjjhwt0DFI3cYvB94=",

	//编码格式
	'charset' => "UTF-8",

	//支付宝网关
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

	//应用ID
	'app_id' => "2021003129691172",

	//异步通知地址,只有扫码支付预下单可用
	'notify_url' => "https://www.astrocms.cn/member/api/public_notify_alipay.html",

	//最大查询重试次数
	'MaxQueryRetry' => "10",

	//查询间隔
	'QueryDuration' => "3"
];