```
$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
if ($_SERVER["SERVER_PROTOCOL"] == "HTTP/1.0" && $host == "captive.apple.com" && $_SERVER["REQUEST_URI"] == "/hotspot-detect.html"){
		echo "<HTML><HEAD><TITLE></TITLE></HEAD><BODY>Success</BODY></HTML>";
		return;
}
```
