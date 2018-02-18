<?php
include 'AmuLog.php';
include 'ChromePhp.php';
$result = show_site();
var_dump($result);


echo amub('add','auth','172.16.1.180').'<br>';
echo amub('del','auth','172.16.1.180').'<br>';
// echo amub('create','auth').'<br>';


function test_echo($b){
	return $b;	
}
echo test_echo('wtf').'<br />';


AmuLog::log('fuck');
echo "fuck";
AmuLog::log('aaaa');

ChromePhp::log('Hello console!');
print_r($_SERVER);
ChromePhp::log($_SERVER);
ChromePhp::warn('something went wrong!');
