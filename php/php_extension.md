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

* 生成扩展模块
`./ext_skel --extname=bdipset`

* 修改config.m4

* 修改xxx.h

* 编译参数

```
./configure --with-php-config=/Data/apps/php/bin/php-config
make LDFLAGS=-lwhhipset
make install
```

* 重启服务 
