*获取源码

*网站结构

*入口文件

*配置文件

*过滤功能


审计方法-通读全文法
disable_functions
disable_classes
safe_mode=off
safe_mode_exec_dir=/var/www/html
file_uploads=on
upload_max_filesize=8M
文件上传目录
upload_tmp_dir=
用户访问目录限制
open_basedir=
display_error=on
error_reporting=E_ALL
error_log=
log_errors=on
log_errors_max_length=1024
magic_quotes_gpc=On
magic_quotes_runtime=Off
allow_url_fopen = on
allow_url_include = Off 


常见危险函数及特殊函数

eval()
assert()
preg_replace()
preg_replace("/test/e",$_GET['h'],"just test");
?h=phpinfo(),phpinfo()将会执行

create_function()
call_user_func()
call_func_array()

require
include
require_once
include_once

命令执行函数

exec()
passthru()
proc_open()
shell_exec()
system()
popen()

文件操作函数
copy 
file_get_contents
file_put_contents
file
fopen
move_uploaded_file
readfile
rename
rmdir
unlink & delete


特殊函数
phpinfo()
symlink()
getenv()
putenv()
dl()

配置相关
ini_get()
ini_set
ini_alter
ini_restore

数字判断
is_numeric

数组相关
in_array



变量覆盖

parse_str()
mb_parse_str
extract()
import_request_variables()

列目录
glob


无参数获取信息
get_defined_vars
get_defined_constants
get_defined_functions
get_includes_files




http://api.map.baidu.com/highacciploc/v1?qcip=42.199.60.86&qterm=pc&ak=E3m5WpmdnG2G4xXpikZ7ai7W1yP1UHGw&coord=bd09ll&extensions=3
