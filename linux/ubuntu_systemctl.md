### 服务的创建

* 切换到/lib/systemd/system/路径下
* 分别创建php\nginx和MySQL的服务文件
    * vim /lib/systemd/system/php-fpm.service
```
[Unit]
Description=The PHP FastCGI Process Manager
After=syslog.target network.target

[Service]
Type=simple
PIDFile=/run/php-fpm.pid
ExecStart=/Data/apps/php7.1.13/sbin/php-fpm --nodaemonize --fpm-config /Data/apps/php7.1.13/etc/php-fpm.conf
ExecReload=/bin/kill -USR2 $MAINPID
ExecStop=/bin/kill -SIGINT $MAINPID

[Install]
WantedBy=multi-user.target

```

    *  vim /lib/systemd/system/nginx-1.13.8.service
```
[Unit]
Descript=nginx-1.13.8
After=syslog.target network.target remote-fs.target nss-lookup.target

[Service]
Type=forking
ExecStart=/Data/apps/nginx1.13.8/sbin/nginx -c /Data/apps/nginx1.13.8/conf/nginx.conf
ExecStop=/Data/apps/nginx1.13.8/sbin/nginx -s top
PrivateTmp=true

[Install]
WantedBy=multi-user.target
```

    * vim /lib/systemd/system/mysql3306.service
```
[Unit]
Description=The MYSQL 3306 Process Manager
After=syslog.target network.target

[Service]
User=amu
Group=amu
Type=simple
ExecStart=/usr/local/mysql/bin/mysqld_safe --defaults-file=/etc/my.cnf
ExecReload=/usr/local/mysql/bin/mysqladmin -uroot -s /usr/local/mysql/data/mysql.sock -proot shutdown && /usr/local/mysql/bin/mysqld_safe --defaults-file=/etc/my.cnf
ExecStop=/usr/local/mysql/bin/mysqladmin -uroot -S /usr/local/mysql/data/mysql.sock -proot shutdown

[Install]
WantedBy=multi-user.target

```

    * vim /lib/systemd/system/mysql3307.service
```
[Unit]
Description=The MYSQL 3307 Process Manager
After=syslog.target network.target

[Service]
User=amu
Group=amu
Type=simple
ExecStart=/usr/local/mysql/bin/mysqld_safe --defaults-file=/etc/my2.cnf
ExecReload=/usr/local/mysql/bin/mysqladmin -uroot -S /usr/local/mysql/data/mysql2.sock -proot shutdown && /usr/local/mysql/bin/mysqld_safe --defaults-file=/etc/my2.cnf
ExecStop=/usr/local/mysql/bin/mysqladmin -uroot -S /usr/local/mysql/data/mysql2.sock -proot shutdown

[Install]
WantedBy=multi-user.target

```
