 echo 1 > /proc/sys/net/ipv4/ip_forward
iptables -t nat -A PREROUTING -p tcp -m set ! --match-set amu src -j DNAT --to-destination 10.42.0.1
iptables -t nat -A POSTROUTING -s 10.42.0.0/24 -j SNAT --to-source 172.16.110.62
iptables -t nat -A POSTROUTING -s 10.42.0.0/24 -j SNAT --to 172.16.110.62
