### 开发环境搭建


#### PHP编译安装

* 下载源码包

* 安装依赖库

```
sudo apt-get install libxml2-dev
sudo apt-get install openssl
sudo apt-get install libssl-dev
sudo apt-get install make
sudo apt-get install curl
sudo apt-get install libcurl4-gnutls-dev
sudo apt-get install libjpeg-dev
sudo apt-get install libpng-dev
sudo apt-get install libmcrypt-dev
```

* 编译

```
'./configure'  '--prefix=/Data/apps/php7.1.13' '--with-config-file-path=/Data/apps/php7.1.13/etc' '--enable-fpm' '--with-fpm-user=amu'

make && make install
```

> 如果需要添加扩展可以重新编译，或者用扩展安装方式，记得重新编译之前要make clean或者重新删除解压
./configure --prefix=/usr/local/php \
--with-config-file-path=/usr/local/php/etc \
--with-apxs2=/usr/local/httpd/bin/apxs \
--with-iconv-dir=/usr/local/lib \
--enable-fpm --with-fpm-user=ghostwu \
--with-iconv-dir=/usr/local/lib \
--enable-mysqlnd \
--with-pdo-mysql=mysqlnd \
--with-openssl \
--with-

* 启动php
```
#测试php-fpm配置
/usr/local/php/sbin/php-fpm -t
/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf -t
 
#启动php-fpm
/usr/local/php/sbin/php-fpm
/usr/local/php/sbin/php-fpm -c /usr/local/php/etc/php.ini -y /usr/local/php/etc/php-fpm.conf
 
#关闭php-fpm
kill -INT `cat /usr/local/php/var/run/php-fpm.pid`
 
#重启php-fpm
kill -USR2 `cat /usr/local/php/var/run/php-fpm.pid`
```
#### nginx编译安装

* 下载源码包

* 安装依赖库
```
# 查看zlib是否安装
dpkg -l | grep zlib
# 解决依赖包openssl安装
sudo apt-get install openssl libssl-dev
# 解决依赖包pcre安装
sudo apt-get install libpcre3 libpcre3-dev
# 解决依赖包zlib安装
sudo apt-get install zlib1g-dev
```

* 编译安装

```
# 配置nginx
cd /usr/local/nginx
sudo ./configure --user=www --group=www --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module --with-http_realip_module
# 编译nginx
sudo make
# 安装nginx
sudo make install
```

* 启动服务
```
# 添加www组
groupadd www
# 创建nginx运行账户www并加入到www组，不允许www用户直接登录系统
useradd -g  www www -s /bin/false
./nginx -t 
# 方法1
/usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf
# 方法2
cd /usr/local/nginx/sbin
./nginx

```

#### 编译安装mysql5.7.13

* 源码下载地址


源码下载地址：http://dev.mysql.com/downloads/mysql/ 
选择Generic Linux (Architecture Independent), Compressed TAR Archive
　　Includes Boost Headers

首先    ： sudo apt-get update
　　第一步： sudo apt-get install cmake -y 搭建跨平台安装（编译工具）。
                  如果版本太低，到http://cmake.org/files/v3.4/cmake-3.4.1.tar.gz
                 下载后手动编译安装

　　第二步： apt-get install git -y

　　第三步： 安装C/C++编译器
                 sudo apt-get install gcc g++ -y (一般来说我们使用的UBUNTU自带的) 
                
　　第四步：安装LINUX常用图形库
               apt-get install libncurses5 libncurses5-dev -y


　　第五步：预编译参数
               cmake . -DCMAKE_INSTALL_PREFIX=/usr/local/mysql -DSYSCONFDIR=/etc -DDEFAULT_CHARSET=utf8                  -DDEFAULT_COLLATION=utf8_general_ci -DMYSQL_DATADIR=/usr/local/mysql/data -DWITH_BOOST=boost  

　　第六步：如果前面没有报错的话
                 make
 
　　第七步：sudo make install 

简单了解MYSQL配置文件
     　　1、进入安装目录/bin文件夹
           mysqld 是最终需要运行的可执行程序。（不过一般我们会使用mysqld_safe 这个脚本来运行)
           我们来执行一下 mysqld --verbose --help 
           这个命令生成所有mysqld选项和可配置变量的列表

      　　 2、如果你想了解配置文件到底放哪了
            mysqld --verbose --help | grep cnf
            这时会发现 /etc/mysql/my.cnf       /etc/my.cnf       ~/.my.cnf
            有这么一行，这代表它mysql会读取配置文件，按顺序读,直至读到位置。
        
      　　 3、假如my.cnf丢失
             （1）、来到你下载的mysql文件夹中有个 supports-files
             （2）、你会发现有个my-default.cnf ,把它拷贝到 前面3个文件夹中任何一个（注意顺序)
                          sudo  cp my-default.cnf  /etc   (案例拷贝到这)
              （3）、同时你要更改my.cnf的所有者
                           chown shenyi:shenyi /etc/my.cnf
重要参数设置
       [client]
        port = 3306
        socket = /usr/local/mysql/data/mysql.sock

        [mysqld]
        port = 3306
        socket = /usr/local/mysql/data/mysql.sock
        basedir = /usr/local/mysql
        datadir  = /usr/local/mysql/data

初始化数据库，这个很重要！
        1、首要根据我们前面设置的数据库目录,/usr/local/mysql/data
        2、来到bin目录
                mysqld --initialize  --user=tiger --basedir=/usr/local/mysql --datadir=/usr/local/mysql/data/
                会告诉你一个临时密码 (root)
                比如：mApr&sfU-6%z
 
運行服務：
        1、直接运行mysqld_safe
        2、进入mysql客户端 ./mysql -u root -p 
        3、输入密码
            修改密码，否则不能运行
            ALTER USER USER() IDENTIFIED BY ‘123’
             ./mysqladmin -u root -p密码 
            shutdown  关闭mysql服务
