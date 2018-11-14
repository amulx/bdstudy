#### 初始化环境

```
pi@raspberrypi:~/code/composer $ composer init

  Welcome to the Composer config generator                                           

This command will guide you through creating your composer.json config.

Package name (<vendor>/<name>) [pi/composer]: 
Description []: composer学习     
Author [, n to skip]: pi chen <test@qq.com>
Minimum Stability []: stable
Package Type (e.g. library, project, metapackage, composer-plugin) []: project
License []: 
Define your dependencies.
Would you like to define your dependencies (require) interactively [yes]? n
Would you like to define your dev dependencies (require-dev) interactively [yes]? n

{
    "name": "pi/composer",
    "description": "composer学习",
    "type": "project",
    "authors": [
        {
            "name": "pi chen",
            "email": "test@qq.com"
        }
    ],
    "minimum-stability": "stable",
    "require": {}
}

Do you confirm generation [yes]? yes
pi@raspberrypi:~/code/composer $ ls
composer.json
```

---

```
pi@raspberrypi:~/code/composer $ cat composer.json 
{
    "name": "pi/composer",
    "description": "composer学习",
    "type": "project",
    "authors": [
        {
            "name": "pi chen",
            "email": "test@qq.com"
        }
    ],
    "minimum-stability": "stable",
    "require": {}
}
```

* 新建相关测试文件

  ```
  ├── composer.json
  ├── config
  │   └── mysql.php
  ├── controller
  │   └── User.php
  ├── helpers.php
  └── index.php
  
  2 directories, 5 files
  ```

* 未引入composer时项目的使用方式

  ```
  pi@raspberrypi:~/code/composer $ cat index.php 
  <?php
  echo 'hello world'.PHP_EOL;
  $db_conf = include 'config/mysql.php';
  print_r($db_conf);
  include 'helpers.php';
  // echo http_get('https://172.16.3.156:444');
  
  include 'controller/User.php';
  $user = new User();
  $user->showName();
  ```

---

#### 增加composer的支持

* 编辑composer.json

  ```
  {
      "name": "pi/composer",
      "description": "composer学习",
      "type": "project",
      "authors": [
          {
              "name": "pi chen",
              "email": "test@qq.com"
          }
      ],
      "autoload":{
          "files":["helpers.php","config/mysql.php"],
          "classmap":["controller/"]
      },
      "minimum-stability": "stable",
      "require": {}
  }
  ```

* 更新composer配置文件

  ```
  pi@raspberrypi:~/code/composer $ composer dumpautoload
  Generating autoload files
  ```

  执行完该命令后，项目文件会发生如下变化

  ```
  ├── composer.json
  ├── config
  │   └── mysql.php
  ├── controller
  │   └── User.php
  ├── helpers.php
  ├── index.php
  └── vendor
      ├── autoload.php
      └── composer
          ├── autoload_classmap.php
          ├── autoload_files.php
          ├── autoload_namespaces.php
          ├── autoload_psr4.php
          ├── autoload_real.php
          ├── autoload_static.php
          ├── ClassLoader.php
          └── LICENSE
  ```

* 修改后的入口文件

  ```
  <?php
  require 'vendor/autoload.php';
  require 'config/mysql.php';
  echo 'hello world'.PHP_EOL;
  
  print_r($db_conf);
  echo http_get('https://172.16.3.156:444');
  $user = new User();
  $user->showName();
  ```

---

#### 通过composer为项目增加扩展包

* [安装包仓库](https://packagist.org/)

* 搜索扩展包

  ![img\composer_ext_package.png)

* 修改composer.json文件

  ```
  pi@raspberrypi:~/code/composer $ cat composer.json 
  {
      "name": "pi/composer",
      "description": "composer学习",
      "type": "project",
      "require":{
          "vrana/notorm":"dev-master"
      },
      "authors": [
          {
              "name": "pi chen",
              "email": "test@qq.com"
          }
      ],
      "autoload":{
          "files":["helpers.php","config/mysql.php"],
          "classmap":["controller/"]
      },
      "minimum-stability": "stable",
      "require": {}
  }
  ```

* 修改安装镜像为国内镜像

  ```
  composer config repo.packagist composer https://packagist.phpcomposer.com
  ```

  ---

  ```
  {
      "name": "pi/composer",
      "description": "composer学习",
      "type": "project",
      "require":{
          "vrana/notorm":"dev-master"
      },
      "authors": [
          {
              "name": "pi chen",
              "email": "test@qq.com"
          }
      ],
      "autoload":{
          "files":["helpers.php","config/mysql.php"],
          "classmap":["controller/"]
      },
      "minimum-stability": "stable",
      "repositories": {
          "packagist": {
              "type": "composer",
              "url": "https://packagist.phpcomposer.com"
          }
      }
  }
  ```

* 开始安装依赖

  ```
  pi@raspberrypi:~/code/composer $ composer install
  Loading composer repositories with package information
  Updating dependencies (including require-dev)
  Package operations: 1 install, 0 updates, 0 removals
    - Installing vrana/notorm (dev-master e49d5d2): Cloning e49d5d2f1b from cache
  Writing lock file
  Generating autoload files
  ```

  ```
  ├── composer.json
  ├── composer.lock
  ├── config
  │   └── mysql.php
  ├── controller
  │   └── User.php
  ├── helpers.php
  ├── index.php
  └── vendor
      ├── autoload.php
      ├── composer
      │   ├── autoload_classmap.php
      │   ├── autoload_files.php
      │   ├── autoload_namespaces.php
      │   ├── autoload_psr4.php
      │   ├── autoload_real.php
      │   ├── autoload_static.php
      │   ├── ClassLoader.php
      │   ├── installed.json
      │   └── LICENSE
      └── vrana
          └── notorm
              ├── composer.json
              ├── NotORM
              │   ├── Cache.php
              │   ├── Literal.php
              │   ├── MultiResult.php
              │   ├── Result.php
              │   ├── Row.php
              │   └── Structure.php
              ├── NotORM.php
              ├── readme.txt
  ```
