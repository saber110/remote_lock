## 云麓谷指纹门锁源码

### 硬件配置

Raspberry Pi B+,PS1802指纹识别模块

### 依赖

* PHP 5.3.10+
* swoole [安装教程](https://github.com/swoole/swoole-src#installation)
* mysql

###  安装

首先安装[wiringpi](http://wiringpi.com/download-and-install/)
```
git clone git://git.drogon.net/wiringPi
cd wiringPi
./build
```

生成所需二进制文件
```
sudo gcc SendUART.c -o SendUART -lwiringPi
sudo gcc close.c -o close -lwiringPi
```

修改权限并执行
```
chmod +x run
sudo ./run
```

### 服务端

rserver文件夹为server部分，部署在自己服务器上。

servers:
* server.php socket server，与pi通信
* socket.php websocket server
* index.php

### 常见问题

在web端进行调试返回null，可能是设备读取权限问题
```
sudo chown :www-data /dev/ttyAMA0
sudo chmod g+rw /dev/ttyAMA0
```
