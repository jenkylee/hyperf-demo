### 说明
```
php必须安装swoole扩展且版本>=4.5

master 分支 最上游稳定分支
pre预生产分支，测试验证分支，master 的下游分支
prod 生产环境分支 pre 下游分支
dev 混合开发分支， 测试分支
其它分支都是 功能分支或 Bug 修复分支，可以删除
```

### 安装
```
git clone https://github.com/jenkylee/hyperf-demo.git
cd hyperf-demo
composer install --prefer-dist --no-dev --optimize-autoloader
composer dump-auto --optimize
cp .env.example .env

vim .env // 编辑 .env，填写数据库，域名等信息

启动方式：
php bin/hyperf.php start
关闭方式：
php bin/hyperf.php stop

生产环境需开启守护配置：
vi /etc/supervisord.d/hyperf-demo.ini
[program:hyperf-demo]
# 设置命令在指定的目录内执行
directory=/data/www/hyperf-demo/
# 这里为您要管理的项目的启动命令
command=php bin/hyperf.php start
# 以哪个用户来运行该进程
user=www
# supervisor 启动时自动该应用
autostart=true
# 进程退出后自动重启进程
autorestart=true
# 进程持续运行多久才认为是启动成功
startsecs=1
# 重试次数
startretries=3
# 整个进程组关闭
stopasgroup=true
# stderr 日志输出位置
stderr_logfile=/data/www/hyperf-demo/runtime/stderr.log
# stdout 日志输出位置
stdout_logfile=/data/www/hyperf-demo/runtime/stdout.log

# 启动supervisord
supervisord -c /etc/supervisord.conf

# 启动 hyperf 应用
supervisorctl start hyperf-demo
# 重启 hyperf 应用
supervisorctl restart hyperf-demo
# 停止 hyperf 应用
supervisorctl stop hyperf-demo  
# 查看所有被管理项目运行状态
supervisorctl status
# 重新加载配置文件
supervisorctl update
# 重新启动所有程序
supervisorctl reload

```

### 更改项目所有者为www用户/组

```
cd /data/www
chown -R www:www hyperf-demo

```
### 部分目录权限
```
cd /data/www
mkdir -p hyperf-demo/runtime
chmod 777 -R hyperf-demo/runtime
chmod 755 -R hyperf-demo/vendor

mkdir -p hyperf-demo/public/upload
chmod 777 -R hyperf-demo/public/upload
```