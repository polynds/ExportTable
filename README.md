
## Export Table

<p align=""><code>Export Table</code>是一个导出数据表DDL的工具，可以导出为Word、PDF、Excel、TXT等文档格式，只需要一个命令就能将数据表导出高颜值的文档，对后端开发者非常友好。</p>


### 功能特性

- [x] 导出为Word
- [ ] 导出PDF、Excel、TXT


### 环境
 - PHP >= 7.4.0
 - Laravel 5.5.0 ~ 9.*

### 安装

> 如果安装过程中出现`composer`下载过慢或安装失败的情况，请运行命令`composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/`把`composer`镜像更换为阿里云镜像。

首先需要安装`laravel`框架，如已安装可以跳过此步骤。如果您是第一次使用`laravel`，请务必先阅读文档 [安装 《Laravel中文文档》](https://learnku.com/docs/laravel/8.x/installation/9354) ！
```bash
composer create-project --prefer-dist laravel/laravel 项目名称 7.*
# 或
composer create-project --prefer-dist laravel/laravel 项目名称
```

安装`export-table`


```
composer require polynds/export-table
```

然后运行下面的命令来发布资源：

```
php artisan vender:publish
```

在该命令会生成配置文件`config/export_table.php`，可以在里面修改连接名称、忽略表名、白名单、输出的样式。

然后运行下面的命令导出文档：

```
php artisan export:table
```

### License

`export-table` is licensed under [The MIT License (MIT)](LICENSE).
