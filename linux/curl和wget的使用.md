```
curl -k --cert client.pem --key key.pem https://www.xxx.com
curl -k --cert all.pem https://www.xxx.com
wget  --no-check-certificate -O - https://127.0.0.1:444/casc/Heart > /tmp/heart.log
curl -I -o /dev/null -s -w %{http_code}"\n" 127.0.0.1:6666
curl -k -d "username=root&password=root" https://127.0.0.1:444
```
