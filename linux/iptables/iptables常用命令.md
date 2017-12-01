iptables -L -n -v


iptables -A FWINPUT -p tcp -m tcp --dport 8090 -j ACCEPT
iptables -A FWINPUT -i -p tcp -m tcp --dport 4443 -j ACCEPT
iptables -A FWINPUT -i -p tcp -m tcp --sport 4443 -j ACCEPT

1、删除所有现有规则
    iptables -F

2、设置默认的chain策略
    iptables -P INPUT DROP
    iptables -P FORWARD DROP
    iptables -P OUTPUT DROP

3、阻止某个特定的IP地址
    #BLOCK_THIS_IP='x.x.x.x'
    #iptables -A INPUT -s "$BLOCK_THIS_IP" -j DRO

4、允许全部进来的SSH
    iptables -A INPUT -i eth0 -p tcp --dport 22 -m state -state NEW,ESTABLISHED -j ACCEPT
    iptables -A OUTPUT -o eth0 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT

5、只允许某个特定网络进来的SSH
    iptables -A INPUT i eth0 -p tcp -s 192.168.200.0/24 -dport 22 -m state --state NEW,ESTABLISHED -j ACCEPT
    iptables -A OUTPUT -o eth0 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT

6、允许进来的HTTP
    iptables -A INPUT -i eth0 -p tcp --dport 80 -m state --state NEW,ESTABLISHED -j ACCEPT
    iptables -A OUTPUT -o eth0 -p tcp --sport 80 -m state --state ESTABLISHED -j ACCEPT

7、多端口（允许进来的SSH、HTTP和HTTPS）
    iptables -A INPUT -i eth0 -p tcp -m multiport -dports 22,80,443 -m state --state NEW,ESTABLISHED -j ACCEPT
    iptables -A OUTPUT -o eth0 -p tcp -m multiport --sport 22,80,443 -m state --state ESTABLISHED -j ACCEPT

8、允许出去的SSH
    iptables -A OUTPUT -o eth0 -p tcp --dport 22 -m state --state NEW,ESTABLISTHED -j ACCEPT
    iptables -A INPUT -i eth0 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT

9、允许外出的SSH，但仅访问某个特定的网络
    iptables -A OUTPUT -o eth0 -p tcp -d 192.168.101.0/24 --dport 22 -m state --state NEW,ESTABLISHED -j ACCEPT
    iptables -A INPUT -i eth0 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT

10. 允许外出的（outgoing） HTTPS

iptables -A OUTPUT -o eth0 -p tcp --dport 443 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A INPUT -i eth0 -p tcp --sport 443 -m state --state ESTABLISHED -j ACCEPT

11. 对进来的 HTTPS 流量做负载均衡

iptables -A PREROUTING -i eth0 -p tcp --dport 443 -m state --state NEW -m nth --counter 0 --every 3 --packet 0 -j DNAT --to-destination 192.168.1.101:443
iptables -A PREROUTING -i eth0 -p tcp --dport 443 -m state --state NEW -m nth --counter 0 --every 3 --packet 1 -j DNAT --to-destination 192.168.1.102:443
iptables -A PREROUTING -i eth0 -p tcp --dport 443 -m state --state NEW -m nth --counter 0 --every 3 --packet 2 -j DNAT --to-destination 192.168.1.103:443

12. 从内部向外部 Ping

iptables -A OUTPUT -p icmp --icmp-type echo-request -j ACCEPT
iptables -A INPUT -p icmp --icmp-type echo-reply -j ACCEPT

13. 从外部向内部 Ping

iptables -A INPUT -p icmp --icmp-type echo-request -j ACCEPT
iptables -A OUTPUT -p icmp --icmp-type echo-reply -j ACCEPT

14. 允许环回（loopback）访问

iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

15. 允许 packets 从内网访问外网

if eth1 is connected to external network (internet)
if eth0 is connected to internal network (192.168.1.x)
iptables -A FORWARD -i eth0 -o eth1 -j ACCEPT

16. 允许外出的  DNS

iptables -A OUTPUT -p udp -o eth0 --dport 53 -j ACCEPT
iptables -A INPUT -p udp -i eth0 --sport 53 -j ACCEPT

17. 允许 NIS 连接

rpcinfo -p | grep ypbind ; This port is 853 and 850
iptables -A INPUT -p tcp --dport 111 -j ACCEPT
iptables -A INPUT -p udp --dport 111 -j ACCEPT
iptables -A INPUT -p tcp --dport 853 -j ACCEPT
iptables -A INPUT -p udp --dport 853 -j ACCEPT
iptables -A INPUT -p tcp --dport 850 -j ACCEPT
iptables -A INPUT -p udp --dport 850 -j ACCEPT

18. 允许某个特定网络 rsync 进入本机

iptables -A INPUT -i eth0 -p tcp -s 192.168.101.0/24 --dport 873 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 873 -m state --state ESTABLISHED -j ACCEPT

19. 仅允许来自某个特定网络的 MySQL 的链接

iptables -A INPUT -i eth0 -p tcp -s 192.168.200.0/24 --dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 3306 -m state --state ESTABLISHED -j ACCEPT

20. 允许 Sendmail 或 Postfix

iptables -A INPUT -i eth0 -p tcp --dport 25 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 25 -m state --state ESTABLISHED -j ACCEPT

21. 允许 IMAP 和 IMAPS

iptables -A INPUT -i eth0 -p tcp --dport 143 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 143 -m state --state ESTABLISHED -j ACCEPT
iptables -A INPUT -i eth0 -p tcp --dport 993 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 993 -m state --state ESTABLISHED -j ACCEPT

22. 允许 POP3 和 POP3S

iptables -A INPUT -i eth0 -p tcp --dport 110 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 110 -m state --state ESTABLISHED -j ACCEPT
iptables -A INPUT -i eth0 -p tcp --dport 995 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 995 -m state --state ESTABLISHED -j ACCEPT

23. 防止 DoS 攻击

iptables -A INPUT -p tcp --dport 80 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT

24. 设置 422 端口转发到 22 端口

iptables -t nat -A PREROUTING -p tcp -d 192.168.102.37 --dport 422 -j DNAT --to 192.168.102.37:22
iptables -A INPUT -i eth0 -p tcp --dport 422 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o eth0 -p tcp --sport 422 -m state --state ESTABLISHED -j ACCEPT

25. 为丢弃的包做日志（Log）

iptables -N LOGGING
iptables -A INPUT -j LOGGING
iptables -A LOGGING -m limit --limit 2/min -j LOG --log-prefix "IPTables Packet Dropped: " --log-level 7
iptables -A LOGGING -j DROP


