laravel-cmf是基于laravel框架开发的后台管理扩展，通过此扩展可以快速构建后台系统。

#### 安装步骤
##### 1 安装扩展
```
composer require xadmin/laravel-cmf:dev-master
```
##### 2 连接数据库
```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```
##### 3 配置APP_URL
```
APP_URL=http://localhost:8000
```
##### 4 设置语言(中文)
###### 修改config/app.php
```
'locale' => 'zh_CN',
```

##### 5 运行安装
```
php artisan laravel-cmf:install
```
##### 6 启动
```
php artisan serve
```
#### 创建后台管理员
```
php artisan laravel-cmf:admin your@email.com --create
```

系统将提示您输入用户名和密码。