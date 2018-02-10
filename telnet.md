一、HTTP协议概述和原理
报文、客户端、服务端



二、Telnet模拟http请求
1、cmd下-》Telnet主机地址80   'telnet 127.0.0.1 80'  回车
2、按下快捷键：ctrl+ ] ，再按下回车键  打开回显功能
3、发送请求报文

GET /http/test.php HTTP/1.1   回车
Host:localhost   回车
（空行）




POST /http/test.php HTTP/1.1
HOST:localhost
Content-type:application/x-www-form-urlencoded
content-length:20

act=query&name=ghost

三、三种方式模拟发送表单


1、请求行：
	POST /http/test.php HTTP/1.1

2、首部
	HOST:localhost
	Content-type:application/x-www-form-urlencoded
	content-length:20

	act=query&name=ghost


