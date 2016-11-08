## 云麓谷 指纹门锁

### 背景

学生组织，工作室的钥匙管理混乱（很常见）

已有内部系统，成员信息数据库，需实现指纹绑定

### 结构

- 硬件配置

Raspberry Pi B+,PS1802指纹识别模块

- 通信(swoole)

PS1802 <--UART(串行异步通信)--> Pi <--（异步非阻塞）--> 公网server <--（web socket,Ajax) --> 客户端

### 未完成

[源码](https://git.oschina.net/monkeywzr/zmr.git)

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
