 **PHP-FPM** 、**Nginx**、 **FastCGI**、 **反向代理**、 **负载均衡**
 ------

## 什么是FastCGI

    FastCGI是一个协议，它是应用程序和WEB服务器的桥梁。Nginx并不能直接与PHP-FPM通信，而是将请求通过FastCGI交给PHP-FPM处理。
    

```
location ~ \.php$ {
    try_files $uri /index.php =404;
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
    fastcgi_buffers 16 16k;
    fastcgi_buffer_size 32k;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

  这里fastcgi_pass就是把php请求转发给php-fpm进行处理。通过*netstat*命令可以看到127.0.0.1:9000这个端口上运行的进程就是php-fpm。

## Nginx反向代理

Nginx反向代理最重要的指令是proxy_pass，如：
```
location ^~ /seckill_query/ {
    proxy_pass http://ris.filemail.gdrive:8090/;
    proxy_set_header Host ris.filemail.gdrive;
}
location ^~ /push_message/ {
    proxy_pass http://channel.filemail.gdrive:8090/;
    proxy_set_header Host channel.filemail.gdrive;
}
location ^~ /data/ {
    proxy_pass http://ds.filemail.gdrive:8087/;
    proxy_set_header Host ds.filemail.gdrive;
}
```
通过location匹配url路径，将其转发到另外一个服务器处理。

## Nginx负载均衡
通过负载均衡*upstream*也可以实现反向代理
负载均衡模块用于从*upstream*指令定义的后端主机列表中选取一台主机。Nginx先使用负载均衡模块找到一台主机，再使用*upstream*模块实现与这台主机的交互。
负载均衡的配置：
```
upstream php-upstream {
    ip_hash;
    server 192.168.0.1;
    server 192.168.0.2;
}
location / {
    root   html;
    index  index.html index.htm;
    proxy_pass http://php-upstream;
}
```
该例定义了一个php-upstream的负载均衡配置，通过proxypass反向代理指令应用这个配置、这里用的iphash算法。
负载均衡也可以用在fastcgi_pass上。
如：`fastcgi_pass http://php-upstream`

## 反向代理和负载均衡是什么关系
反向代理和负载均衡这两个词经常出现在一起，但他们实际上是不同的概念，负载均衡它更多的是强调一种算法或策略，将请求分布到不同的机器上，因此也起到了反向代理的作用。

## proxy_pass 和 fastcgi_pass 的区别
一个是反向代理模块，一个是转发给fastcgi后端处理。
