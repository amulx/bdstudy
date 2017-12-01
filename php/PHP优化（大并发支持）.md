*php-fpm* 、 *一台机子运行多个php-fpm*
------
以下阐述主要是为了解决在LNMP环境中遇到高并发下*Nginx出现502*的问题
一般情况下，服务器在遇到高并发时Nginx发起的连接数远远超过了*php-fpm*所能处理的数目，导致端口（或者socket）频繁被锁，造成堵塞，所以容易出现502的问题。

## 第一步：修改php-fpm运行方式为   socket
    打开/Data/apps/php7/etc/php-fpm.d/www.conf文件，
    并将listen = 127.0.0.1:9000修改为listen = /dev/shm/php-cgi.sock


>Socket是使用unix domain socket连接套接字/dev/shm/php-cgi.sock（很多教程使用路径/tmp，而路径/dev/shm是个tmpfs，速度比磁盘快得多）listen = /dev/shm/php-cgi.sock

## 第二步：重启fpm

### 1.测试php-fpm配置
./php-fpm -t
./php-fpm -c /Data/apps/php7/etc/php.ini -y /Data/apps/php7/etc/php-fpm.d/www.conf.1 -t
### 2.启动php-fpm
./php-fpm
./php-fpm -c /Data/apps/php7/etc/php.ini -y /Data/apps/php7/etc/php-fpm.d/www.conf.1
./php-fpm -c /Data/apps/php7/etc/php.ini --fpm-config /Data/apps/php7/etc/php-fpm.d/www.conf.1
### 3.关闭php-fpm
kill -INT `cat /usr/local/php/var/run/php-fpm.pid`


## 第三步：修改nginx的配置文件

```
upstream phpbackend {
    server unix:/dev/shm/php-cgi.sock weight=100 max_fails=10 fail_timeout=30;
    server unix:/dev/shm/php-cgi.sock.1 weight=100 max_fails=10 fail_timeout=30;
}

server {
    server_name 127.0.0.1;
    listen 444; 
    ssl on;
    ssl_certificate /Data/apps/nginx/conf/33iq.crt;
    ssl_certificate_key /Data/apps/nginx/conf/33iq_nopass.key;
    ssl_protocols SSLv2 TLSv1;
    ssl_ciphers ECDHE-RSA-AES256-SHA384:AES256-SHA256:HIGH:!MD5:!aNULL:!eNULL:!NULL:!DH:!EDH:!AESGCM;
    client_max_body_size 512M;
    add_header X-Frame-Options SAMEORIGIN;
    charset utf-8;  
    root   /Data/apps/wwwroot/firewall/apps/admin; 
        index  index.html index.htm index.php;
        location / {
        index  index.htm index.html index.php;  
            #访问路径的文件不存在则重写URL转交给ThinkPHP处理  
            if (!-e $request_filename){
                rewrite ^(.*)$ /index.php last;
            }
    }
    
    location ~ \.php(.*)$ {
        fastcgi_pass    phpbackend;
        #fastcgi_pass    unix:/dev/shm/php-cgi.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        fastcgi_param  PATH_INFO  $fastcgi_path_info;
        # fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
        include        fastcgi_params;
    }
 }
```

## 第四步：重启nginx和php-fpm（php-fpm启动时指定相应的配置文件）
./nginx -s reload

可以看到php-cgi.sock文件unix套接字类型
ls -al /dev/shm