<?php
header("Content-type: text/html; charset=utf-8"); 
$title = "主机配置";
$html  = file_get_contents('test.html');  
  
//使用方法-------------------------
echo (cword($html,$title)); //转换中文并忽视错误
// echo (cword($html,iconv("UTF-8","GB2312//IGNORE",$title))); //转换中文并忽视错误
//----------------------------------------
  
function cword($data,$fileName='')
{
    if(empty($data)) return '';
  
    $data = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">'.$data.'</html>';
    $dir  = "./docfile/".date("Ymd")."/";
  
    if(!file_exists($dir)) mkdir($dir,777,true);
  
    if(empty($fileName))
    {
        $fileName=$dir.date('His').'.doc';
    }
    else
    {
        $fileName =$dir.$fileName.'.doc';
    }
  
    $writefile = fopen($fileName,'wb') or die("创建文件失败"); //wb以二进制写入
    fwrite($writefile,$data);
    fclose($writefile);
    return $fileName;
}