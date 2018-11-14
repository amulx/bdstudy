## 前言

作为一名后台开发，对数据库进行基准测试，以掌握数据库的性能情况是非常必要的。本文介绍了MySQL基准测试的基本概念，以及使用sysbench对MySQL进行基准测试的详细方法。

**文章有疏漏之处，欢迎批评指正。**

## 目录

[一、基准测试简介]()

　　[1、什么是基准测试]()

　　[2、基准测试的作用]()

　　[3、基准测试的指标]()

　　[4、基准测试的分类]()

[二、sysbench]()

　　[1、sysbench简介]()

　　[2、sysbench安装]()

　　[3、sysbench语法]()

　　[4、sysbench使用举例]()

　　[5、测试结果]()

[三、建议]()

# 一、基准测试简介

## 1、什么是基准测试

数据库的基准测试是对数据库的性能指标进行定量的、可复现的、可对比的测试。

**基准测试与压力测试**

基准测试可以理解为针对系统的一种压力测试。但基准测试不关心业务逻辑，更加简单、直接、易于测试，数据可以由工具生成，不要求真实；而压力测试一般考虑业务逻辑(如购物车业务)，要求真实的数据。

## 2、基准测试的作用

对于多数Web应用，整个系统的瓶颈在于数据库；原因很简单：Web应用中的其他因素，例如网络带宽、负载均衡节点、应用服务器（包括CPU、内存、硬盘灯、连接数等）、缓存，都很容易通过水平的扩展（俗称加机器）来实现性能的提高。而对于MySQL，由于数据一致性的要求，无法通过增加机器来分散向数据库写数据带来的压力；虽然可以通过前置缓存（Redis等）、读写分离、分库分表来减轻压力，但是与系统其它组件的水平扩展相比，受到了太多的限制。

而对数据库的基准测试的作用，就是分析在当前的配置下（包括硬件配置、OS、数据库设置等），数据库的性能表现，从而找出MySQL的性能阈值，并根据实际系统的要求调整配置。

## 3、基准测试的指标

常见的数据库指标包括：

- TPS/QPS：衡量吞吐量。
- 响应时间：包括平均响应时间、最小响应时间、最大响应时间、时间百分比等，其中时间百分比参考意义较大，如前95%的请求的最大响应时间。。
- 并发量：同时处理的查询请求的数量。

## 4、基准测试的分类

对MySQL的基准测试，有如下两种思路：

（1）针对整个系统的基准测试：通过http请求进行测试，如通过浏览器、APP或postman等测试工具。该方案的优点是能够更好的针对整个系统，测试结果更加准确；缺点是设计复杂实现困难。

（2）只针对MySQL的基准测试：优点和缺点与针对整个系统的测试恰好相反。

在针对MySQL进行基准测试时，一般使用专门的工具进行，例如mysqlslap、sysbench等。其中，sysbench比mysqlslap更通用、更强大，且更适合Innodb（因为模拟了许多Innodb的IO特性），下面介绍使用sysbench进行基准测试的方法。

# 二、sysbench

## 1、sysbench简介

sysbench是跨平台的基准测试工具，支持多线程，支持多种数据库；主要包括以下几种测试：

- cpu性能
- 磁盘io性能
- 调度程序性能
- 内存分配及传输速度
- POSIX线程性能
- 数据库性能(OLTP基准测试)

本文主要介绍对数据库性能的测试。

## 2、sysbench安装

本文使用的环境时CentOS 6.5；在其他Linux系统上的安装方法大同小异。MySQL版本是5.6。

（1）下载解压

```shell
wget https://github.com/akopytov/sysbench/archive/1.0.zip -O "sysbench-1.0.zip"
unzip sysbench-1.0.zip
cd sysbench-1.0
```

（2）安装依赖

```shell
yum install automake libtool –y
```



 （3）安装

安装之前，确保位于之前解压的sysbench目录中。

```shell
./autogen.sh
./configure
export LD_LIBRARY_PATH=/usr/local/mysql/include #这里换成机器中mysql路径下的include
make
make install
```



（4）安装成功

```shell
[root@test sysbench-1.0]# sysbench --version
sysbench 1.0.9
```



## 3、sysbench语法

执行sysbench –help，可以看到sysbench的详细使用方法。

sysbench的基本语法如下：

**sysbench [options]... [testname] [command]**

下面说明实际使用中，常用的参数和命令。

### （1）command

command是sysbench要执行的命令，包括prepare、run和cleanup，顾名思义，prepare是为测试提前准备数据，run是执行正式的测试，cleanup是在测试完成后对数据库进行清理。

### （2）testname

testname指定了要进行的测试，在老版本的sysbench中，可以通过--test参数指定测试的脚本；而在新版本中，--test参数已经声明为废弃，可以不使用--test，而是直接指定脚本。

例如，如下两种方法效果是一样的：

```shell
sysbench --test=./tests/include/oltp_legacy/oltp.lua
sysbench ./tests/include/oltp_legacy/oltp.lua
```



测试时使用的脚本为lua脚本，可以使用sysbench自带脚本，也可以自己开发。对于大多数应用，使用sysbench自带的脚本就足够了。不同版本的sysbench中，lua脚本的位置可能不同，可以自己在sysbench路径下使用find命令搜索oltp.lua。P.S.：大多数数据服务都是oltp类型的，如果你不了解什么是oltp，那么大概率你的数据服务就是oltp类型的。

### （3）options

sysbench的参数有很多，其中比较常用的包括：

**MySQL****连接信息参数**

- --mysql-host：MySQL服务器主机名，默认localhost；如果在本机上使用localhost报错，提示无法连接MySQL服务器，改成本机的IP地址应该就可以了。
- --mysql-port：MySQL服务器端口，默认3306
- --mysql-user：用户名
- --mysql-password：密码

**MySQL****执行参数**

- --oltp-test-mode：执行模式，包括simple、nontrx和complex，默认是complex。simple模式下只测试简单的查询；nontrx不仅测试查询，还测试插入更新等，但是不使用事务；complex模式下测试最全面，会测试增删改查，而且会使用事务。可以根据自己的需要选择测试模式。
- --oltp-tables-count：测试的表数量，根据实际情况选择
- --oltp-table-size：测试的表的大小，根据实际情况选择
- --threads：客户端的并发连接数
- --time：测试执行的时间，单位是秒，该值不要太短，可以选择120
- --report-interval：生成报告的时间间隔，单位是秒，如10

## 4、sysbench使用举例

在执行sysbench时，应该注意：

（1）尽量不要在MySQL服务器运行的机器上进行测试，一方面可能无法体现网络（哪怕是局域网）的影响，另一方面，sysbench的运行（尤其是设置的并发数较高时）会影响MySQL服务器的表现。

（2）可以逐步增加客户端的并发连接数（--thread参数），观察在连接数不同情况下，MySQL服务器的表现；如分别设置为10,20,50,100等。

（3）一般执行模式选择complex即可，如果需要特别测试服务器只读性能，或不使用事务时的性能，可以选择simple模式或nontrx模式。

（4）如果连续进行多次测试，注意确保之前测试的数据已经被清理干净。

下面是sysbench使用的一个例子：

### （1）准备数据

```shell
sysbench ./tests/include/oltp_legacy/oltp.lua --mysql-host=192.168.10.10 --mysql-port=3306 --mysql-user=root --mysql-password=123456 --oltp-test-mode=complex --oltp-tables-count=10 --oltp-table-size=100000 --threads=10 --time=120 --report-interval=10 prepare
```



其中，执行模式为complex，使用了10个表，每个表有10万条数据，客户端的并发线程数为10，执行时间为120秒，每10秒生成一次报告。

 ![](https://images2017.cnblogs.com/blog/1174710/201709/1174710-20170930175906606-700759255.png)

### （2）执行测试

将测试结果导出到文件中，便于后续分析。

```shell
sysbench ./tests/include/oltp_legacy/oltp.lua --mysql-host=192.168.10.10 --mysql-port=3306 --mysql-user=root --mysql-password=123456 --oltp-test-mode=complex --oltp-tables-count=10 --oltp-table-size=100000 --threads=10 --time=120 --report-interval=10 run >> /home/test/mysysbench.log
```



### （3）清理数据

执行完测试后，清理数据，否则后面的测试会受到影响。

```shell
sysbench ./tests/include/oltp_legacy/oltp.lua --mysql-host=192.168.10.10 --mysql-port=3306 --mysql-user=root --mysql-password=123456 cleanup
```



## 5、测试结果

测试结束后，查看输出文件，如下所示：

![](https://images2017.cnblogs.com/blog/1174710/201709/1174710-20170930175919700-791017735.png)

其中，对于我们比较重要的信息包括：

queries：查询总数及qps

transactions：事务总数及tps

Latency-95th percentile：前95%的请求的最大响应时间，本例中是344毫秒，这个延迟非常大，是因为我用的MySQL服务器性能很差；在正式环境中这个数值是绝对不能接受的。

# 三、建议

下面是使用sysbench的一些建议。

1、在开始测试之前，应该首先明确：应采用针对整个系统的基准测试，还是针对MySQL的基准测试，还是二者都需要。

2、如果需要针对MySQL的基准测试，那么还需要明确精度方面的要求：是否需要使用生产环境的真实数据，还是使用工具生成也可以；前者实施起来更加繁琐。如果要使用真实数据，尽量使用全部数据，而不是部分数据。

3、基准测试要进行多次才有意义。

4、测试时需要注意主从同步的状态。

5、测试必须模拟多线程的情况，单线程情况不但无法模拟真实的效率，也无法模拟阻塞甚至死锁情况。

# 参考文献

http://blog.csdn.net/oahz4699092zhao/article/details/53332105
