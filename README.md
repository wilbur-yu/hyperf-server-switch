> 搬运自: [如何让 Hyperf 只启动一个服务](https://zhuanlan.zhihu.com/p/342893609)
# 安装
```bash
composer require wilbur-yu/hyperf-server-switch
```
# 配置
config/autoload/server.php
```php
'servers' => [
        [
            'name'      => 'http',
            'type'      => Server::SERVER_HTTP,
            'host'      => '127.0.0.1',
            'port'      => 9801,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                Event::ON_REQUEST => [Hyperf\HttpServer\Server::class, 'onRequest'],
            ],
        ],
        [
            'name'      => 'http2',
            'type'      => Server::SERVER_HTTP,
            'host'      => '0.0.0.0',
            'port'      => 9502,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                Event::ON_REQUEST => ['HttpServer2', 'onRequest'],
            ],
        ],
    ],
```
# 使用
1. 启动所有 server
```bash
php bin/hyperf.php start
```
2. 启动某个 server
```bash
php bin/hyperf.php -S http

// or

php bin/hyperf.php -S http2
```