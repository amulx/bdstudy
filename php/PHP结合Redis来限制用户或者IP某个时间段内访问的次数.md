```
$redis = new Redis();    
$redis->connect('127.0.0.1', 6379);   

//获取客户端真实ip地址  
function get_real_ip(){  
    static $realip;  
    if(isset($_SERVER)){  
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){  
            $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
        }else if(isset($_SERVER['HTTP_CLIENT_IP'])){  
            $realip=$_SERVER['HTTP_CLIENT_IP'];  
        }else{  
            $realip=$_SERVER['REMOTE_ADDR'];  
        }  
    }else{  
        if(getenv('HTTP_X_FORWARDED_FOR')){  
            $realip=getenv('HTTP_X_FORWARDED_FOR');  
        }else if(getenv('HTTP_CLIENT_IP')){  
            $realip=getenv('HTTP_CLIENT_IP');  
        }else{  
            $realip=getenv('REMOTE_ADDR');  
        }  
    }  
    return $realip;  
}  

//这个key记录该ip的访问次数 也可改成用户id   
$key = get_client_ip();  //该Key记录访问的次数，目前是以IP为例，也可以把用户id作为key，如userid_123456
  
//限制次数为3次。  
$limit = 3;  
  
$check = $redis->exists($key);  
if($check){  
    $redis->incr($key);  
    $count = $redis->get($key);  
    if($count > 3){  
        exit('已经超出了限制次数');  
    }  
}else{  
    $redis->incr($key);  
    //限制时间为60秒   
    $redis->expire($key,60);  
}  
  
$count = $redis->get($key);  
echo '第 '.$count.' 次请求';
```