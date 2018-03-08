------

## 什么是suricata

Suricata是一个网络入侵检测和防护引擎，由开放信息安全基金会及其支持的厂商开发。该引擎是多线程的，内置支持IPV6。可加载现有的Snort规则和签名，支持 Barnyard 和 Barnyard2工具。使用pcap提供的接口进行抓包，运行前电脑必须安装有pcap才可以使用。
Suricata是一个高性能的网络IDS，IPS和网络安全监控引擎。
>IPS：入侵预防系统(IPS: Intrusion Prevention System)是电脑网络安全设施，是对防病毒软件（Antivirus Programs）和防火墙(Packet Filter, Application Gateway)的补充。 入侵预防系统(Intrusion-prevention system)是一部能够监视网络或网络设备的网络资料传输行为的计算机网络安全设备，能够即时的中断、调整或隔离一些不正常或是具有伤害性的网络资料传输行为。是新一代的侵入检测系统（IDS）。

>IDS：英文“Intrusion Detection Systems”的缩写，中文意思是“入侵检测系统”。依照一定的安全策略，通过软、硬件，对网络、系统的运行状况进行监视，尽可能发现各种攻击企图、攻击行为或者攻击结果，以保证网络系统资源的机密性、完整性和可用性。
至于IDS和IPS的区别，可以查看下网络中其他文章，本人理解：
IDS只是发现攻击、产生报警，而IPS不但可以发现攻击，更重要的是针对攻击采取行动

## 软件架构
### Rules
Suricata基于规则检测和控制数据流量，所有规则的配置文件保存在rules目录内。
### Log
Log目录来存放各种日志文件
### Suricata.yaml
Suricata最重要的配置文件，用户配置网络、接口、日志文件和规则文件等。通过#控制开启或关闭配置。
### Classification.config
对告警信息的描述配置文件，使管理员可以区分事件。
### Reference.config
设置外部攻击识别系统引用的URL


## 程序设置及功能
### suricata.yaml配置
* suricata 网络配置
配置suricata监控的网络地址，支持变量配置，变量名为大写

* 配置规则开启或关闭
设置rules、classification.config、reference.config及rhreshold.config文件路径，调整规则的应用和关闭。

* 设置输出
输出设置有三个部分
输出状态：配置全局输出
输出日志：配置具体输出日志的开启、格式和路径等。
输出方式：配置日志输出的具体方式、显示器、文件或系统日志。

* 基本监听设置
基本监听接口及数据包捕获配置。

* 应用层协议配置
配置应用层协议解析，启用部分有‘yes’、‘no’、‘detection-only’三个选项，分别对应：检测并解析、不开启、只检测不解析。

* 高级配置
run-as：是指可以运行suricata的用户及用户组
coredump：配置suricata核心转储配置
host-mode：设置suricata设置是监听设备还是路由器。
unix-command：开启通过unix命令使用外部工具连接suricata获取信息或修改配置。
magic-file：magic文件路径
engine-analysis：开启配置后，各模块会打印模块配置到默认日志内。
pcre：为pcre支持递归和匹配
host-os-plicy：配置检测设备系统及ip地址，增加suricata数据检测、处理、还原的能力。
defrag：数据包碎片整理
flow：设置流最大可用内存及紧急模式等。
flow-timeouts：通过配置各协议时间限制流在内存中的时间。
stream：stream模块可以跟踪tcp连接。该模块包含stream-tarcking和reassembly-engine
detect：检测模块配置，检测模块为内部组生成签名，并可以通过修改配置文件去使用签名和管理内存、cpu的使用。
profiling：性能分析设置。
nfq：nfqueue设置
nflog：设置nflog。
netmap：netmap支持设置
pfring：使用pf_ring库增强libcap的抓包性能，执行抓包。配置网卡，支持负载均衡
ipfw    ：FreeBSD上防火墙 
napatech：napatech网卡支持设置
mpipe：
cuda：

### Rules配置

Rules内的规则配置支持三种方式：proofpoint规则、snort规则和自定义规则。同时支持使用Oinkmaster规则管理系统。
Snort Rules

1.  规则头: 包含规则动作、协议、源IP地址，源端口，方向，目的IP，目的端口。
规则动作：alert(使用选择的报警方法生成一个警报，然后记录这个包)
        Log（记录包）
        Pass（丢弃包）
        Activate（告警并激活另一条dynamic规则）
        Dynamic（保持空闲直到被一条activeta规则激活。）
        Drop（阻止并记录数据包）
        Reject（阻止并记录数据包，tcp协议包发送tcp reset，udp发送icmp端口不可带信息到主机）
        （只阻止不记录）
    协议：snort暂只支持TCP、UDP、ICMP和IP协议,Suricata增加HTTP，FTP，TLS（这包括SSL），SMB和DNS应用层协议
    ip地址：仅支持ip及掩码设置，不支持域名解析，多个ip地址是可以使用‘[  ]’,各ip或ip段之间使用‘，’分隔。否定运算符用‘！’表示。‘any’表示匹配任意ip。
    方向：‘->’表示数据流的方向，双向符号以‘<>’表示。
端口：‘any’表示任何端口，静态端口直接在ip后接端口号，端口否定操作符用‘！’表示。端口范围以‘：’表示，位于端口号左边表示小于或等于端口号，右侧为大于或等于端口号。
    Activate和dynamic规则：activates除包含一个activates选择域外和alert一样，dynamic包含一个不同的选择域activated_by和count域。Activate规则内的activates后接独立数字，dynamic规则内的activated_by后接相同的数字就将两个规则进行了关联。count后接的数字表示dynamic规则被触发后记录的数据包数。例：
        activate icmp any any -> any any (activated :111)
        dynamic tcp any any -> any 23 (activated_by: 111; count:20)
2.  规则选项：规则选项部分包含报警消息内容和要检查的包的具体部分。
规则选项包含在规则头后的‘（ ）’内，各规则选项以‘；’分割，规则选项和参数间用‘：’.
        snort规则关键字：
        msg - 在报警和包日志中打印一个消息。
        logto - 把包记录到用户指定的文件中而不是记录到标准输出。
        ttl - 检查ip头的ttl的值。
        tos 检查IP头中TOS字段的值。
        id - 检查ip头的分片id值。
        ipoption 查看IP选项字段的特定编码。
        fragbits 检查IP头的分段位。
        dsize - 检查包的有效净荷大小的值 。净荷：指用户原始数据。
        flags -检查tcp flags的值。
        seq - 检查tcp顺序号的值。
        ack - 检查tcp应答（acknowledgement）的值。
        window -测试TCP窗口域的特殊值。
        itype - 检查icmp type的值。
        icode - 检查icmp code的值。
        icmp_id - 检查ICMP ECHO ID的值。
        icmp_seq - 检查ICMP ECHO 顺序号的值。
        content - 在包的净荷中搜索指定的样式。
        content-list 在数据包载荷中搜索一个模式集合。
        offset - content选项的修饰符，设定开始搜索的位置 。
        depth - content选项的修饰符，设定搜索的最大深度。
        nocase - 指定对content字符串大小写不敏感。
        session - 记录指定会话的应用层信息的内容。
        rpc - 监视特定应用/进程调用的RPC服务。
        resp - 主动反应（切断连接等）。
        react - 响应动作（阻塞web站点）。
        reference - 外部攻击参考ids。
        sid - snort规则id。
        rev - 规则版本号。
        classtype - 规则类别标识。
        priority - 规则优先级标识号。
        uricontent - 在数据包的URI部分搜索一个内容。
        tag - 规则的高级记录行为。
        ip_proto - IP头的协议字段值。
        sameip - 判定源IP和目的IP是否相等。
        stateless - 忽略状态的有效性。
        regex - 通配符模式匹配。
        distance - 强迫关系模式匹配所跳过的距离。
        within - 强迫关系模式匹配所在的范围。
        byte_test - 数字模式匹配。
        byte_jump - 数字模式测试和偏移量调整。
        flow:这个选项要和TCP流重建联合使用。它允许规则只应用到流量流的某个方向上。这将允许规则只应用到客户端或者服务器端。
常见关键字：
        msg:在报警和包日志中打印消息。格式：(msg :”str”)
        reference:允许规则引用外部工具识别系统。格式：（reference:cve,can-2000-15;）
        gid:用于表示当某一出发规则的事件发生时，标识是snort的哪一部分生成。格式：（gid：10000）
        sid:用来做规则的唯一标识。格式：（sid:10000）
        rev:用来做snort规则修订的唯一标识，修改规则id、签名和说明时需更新，该选项需要和sid一起使用。格式：（sid:1000; rev:1;）
        classtype:用于对攻击检测的规则和产生的日志进行分类，引用的classtype名称需要首先在                                                                                        classification.config配置。格式:（classtype:classtypename;）
priority :制定规则严重级别，可以覆盖classtype分配的默认级别。优先级高的规则会先检查，格式：（priority：10；）
    3.  函数引用
        规则配置可以引用suricata.yaml内设定的函数。
c)  Classification.config设置
    规则选项中classtype选项的配置文件。
    配置格式：config classification: not-suspicious , Not Suspicious Traffic ,3
                        配置头：        名称，     描述，             告警级别

        例子：classification.config内配置: config classification: baidu, search something,4
                规则配置：alert tcp any any -> 123.125.144.144 443(classtype:baidu)
当任意主机访问123.125.144.144的443端口时，告警信息内包含[classtype：search something[priority : 4］
d)  Suricata命令行

## suricata与snort对比

| 参数        | suricata    | snort   |
| --------      | -------: | :-----: |
|IPS支持  | 编译时开启 | 配置内开启 |
| 规则 | snort规则、EmergingThreats规则、VRT::Snort规则|SO规则、EmergingThreats规则|
|线程|多线程|单线程|
|安装方式|手动编译安装|安装包安装|
|文档|suricata wiki | 官网及网络上大量资料|
|输出格式|Flat file、database、unifend2、json|
|IPv6|全支持|编译时开启|
|抓包方式|PF_RING,cua,netmap,af-packet|libpcap|
|配置文件|suricata.yaml, classification.config, reference.config, threshold.config|snort.conf,threshold.conf|
|支持前端|sguil,Aanval,BASE,FPCGUI,snortsnarf|


## 常用命令
| 参数 | 说明|
| --------| ---------|
|-c           | 配置文件路径，例如:suricata.yaml存放路径|
|-T   | 测试配置文件，和-c一起使用，例如：./suricata -c /home/test/suricata.yaml -T |
|-i     | 以pcap live模式运行|
|-F | bpf filter file|
|-r         | run in pcap file/offline mode(应该是读包模式)|
|-s    | path to signature file loaded in addition to suricata.yaml settings (optional)|
|-S    | path to signature file loaded exclusively (optional)|
|-l    | 默认的log目录|
|-D          | daemon方式运行|
|-V           | 版本信息|
|--list-app-layer-protos   | 列出支持的应用层协议|
|--list-keywords[=all|csv|] |列出执行关键字|
|--list-runmodes   | 列出运行模式。例如:PCAP_DEV、PCAP_FILE、PF_RING、NFQ、IPFW、ERF_FILE、ERF_DAG、AF_PACKET_DEV|
|--engine-analysis | 打印分析结果|
|--pidfile  | write pid to this file (only for daemon mode)|
|--init-errors-fatal |  enable fatal failure on signature init error|
|--dump-config     | show the running configuration|
|--build-info      | display build information|
|--pcap[=]   | run in pcap mode, no value select interfaces from suricata.yaml|
|--pcap-buffer-size | size of the pcap buffer value from 0 - 初始化中MAX值|
|--erf-in   |  process an ERF file|




------

#### suricata中redis的配置部分
```
      redis:
        server: 127.0.0.1
        port: 6379
        mode: channel
        pipelining:
          enabled: yes ## set enable to yes to enable query pipelining
          batch-size: 1000 ## number of entry to keep in buffer
```
