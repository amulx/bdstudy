### php动态库扩展开发步骤

共计两部分:

    * c语言动态库开发
    * php扩展开发

#### C语言开发步骤

* 新建 hello.h

```
#ifndef TEST_HEADER_FILE
#define TEST_HEADER_FILE

#include <stdlib.h>
#include <string.h>

char * show_site();

#endif
```

* 新建hello.c

```
#include "hello.h"

char * show_site()
{
	char *site= malloc(15 * sizeof(char));
	strcpy(site,"www.amu.com");
	return site;
}
```
* 将上面两个文件编译生成hello.so

```
gcc -g -O0 -fPIC -shared -o hello.so ./hello.c ./hello.h
```
或者
```
gcc -O -c -fPIC -o hello.o hello.c
gcc -shared -o libhello.so hello.o
```

* 编写测试用例,新建test.c

```
#include<stdio.h>
#include "hello.h"

void main(){
	printf("result:%s\n", show_site());
}
```

* 依赖编译,测试,并运行生成的可执行文件进行测试

```
gcc -o test test.c -L. -lhello
./test
```
或

```


```

* 将编译生成的.so文件放到系统中

```
echo /usr/local/lib > /etc/ld.so.conf.d/local.conf
cp libhello.so /usr/local/lib
/sbin/ldconfig
```
#### PHP扩展开发

* 编写amu.def

```
string amua(string message)
string amub(string type,string name,string ip)

```

* 生成扩展模块
`./ext_skel --extname=amu --proto=amu.def --no-help

* 修改config.m4

```
##指定PHP模块的工作方式，动态编译选项，如果想通过.so的方式接入扩展，请去掉前面的dnl注释
PHP_ARG_WITH(helloworld, for helloworld support,
Make sure that the comment is aligned:
[  --with-helloworld             Include helloworld support])

dnl Otherwise use enable:

##指定PHP模块的工作方式，静态编译选项，如果想通过enable的方式来启用，去掉dnl注释
PHP_ARG_ENABLE(helloworld, whether to enable helloworld support,
Make sure that the comment is aligned:
[  --enable-helloworld           Enable helloworld support])
```
* 修改xxx.h
```
PHP_FUNCTION(amub)
{
        char *type = NULL;
        char *name = NULL;
        char *ip = NULL;
        /*      char *result  = NULL;*/
        zend_string *result;
        int argc = ZEND_NUM_ARGS();
        size_t type_len;
        size_t name_len;
        size_t ip_len;
    

        if (zend_parse_parameters(argc, "sss", &type, &type_len, &name, &name_len, &ip, &ip_len) == FAILURE) 
                RETURN_FALSE;

        result = strpprintf(0, "ipset %s %s %s", type, name,ip);
        RETURN_STR(result);    
        RETURN_TRUE;
        php_error(E_WARNING, "amub: not yet implemented");
}

```

* 编译参数

```
./configure --with-php-config=/Data/apps/php/bin/php-config
make LDFLAGS=-lwhhipset
make install
```

* 重启服务 
