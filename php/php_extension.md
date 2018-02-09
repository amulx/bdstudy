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

* 编写测试用例,新建test.c

```
#include<stdio.h>
#include "hello.h"

void main(){
	printf("result:%s\n", show_site());
}
```

* 依赖编译,测试

```
gcc -o test test.c -L. -lhello
```

#### PHP扩展开发
