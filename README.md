# ZFAKA发卡系统(本系统基于yaf+layui开发)
>演示地址：http://faka.zlkb.net/

# 一、系统介绍
>包含自动/手工发卡功能，有会员中心和后台中心。

1.1 会员模块
* 默认情况下，不支持注册，当然后台可以开放注册；

* 注册成会员可查看历史购买记录。
	
1.2 购买模块
* 支持自动发卡和手工发卡模式；

1.3 后台模块
* 包含订单模块、商品模块、配置模块、卡密导入导出等；
	
1.4 补充关于支付渠道问题
* 支付方式：支付宝当面付，码支付

>不要问我可不可以支持，只要你提供测试账户与接口文档，一般1－2周我都会更新上去；


# 二、系统部署

## 2.1 环境安装

### 2.1.1 lnmp环境
>参考 https://zlkb.net/241.html [如何安装lnmp]

此环境下需要在lnmp安装目录下，运行tools/remove_open_basedir_restriction.sh，用于取消防跨站攻击(open_basedir);添加vhost站点后，把nginx配置中的root路径添加上public即可；

### 2.1.2 宝塔环境
>参考：这里我找个时间用宝塔环境给大家做一个演示吧。

此环境下需要在PHP配置的扩展配置下，关闭PATH_INFO；您也可以在/www/server/nginx/conf/目录下，找到对应的配置文件例如：enable-php-71.conf,然后删除掉include pathinfo.conf;即可正常安装使用;


## 2.2 环境配置
YAF扩展安装
>参考：https://zlkb.net/243.html [Centos系统的lnmp环境下安装yaf扩展]

>补充：php.ini中一定要配置 yaf.use_namespace=1

## 2.3 rewrite配置

* nginx下rewrite规则
<pre>      location / {
                if (!-e $request_filename) {
                        rewrite ^/(.*)$ /index.php?$1 last;
                }
        }
</pre> 

<pre>#####################################################</pre> 

## 特别补充说明：yaf的环境安装比较麻烦，需要注意一些问题；

* 务必：配置nginx vhost中root路径一定要加上public目录，例如:  /alidata/wwwroot/faka.zlkb.net/public;

* 务必：配置nginx vhost中一定要添加rewrite规则

* 务必：取消防跨站攻击(open_basedir)

* 务必：项目运行给站点用户权限

* 建议：mysql数据库配置时建议这样操作参考：https://zlkb.net/302.html

<pre>#######################################################</pre> 

## 2.4 下载代码
<pre>
	git clone https://github.com/zlkbdotnet/zfaka.git [这是最新测试版]
</pre> 

>稳定版：请访问这里下载：https://github.com/zlkbdotnet/zfaka/releases　

## 2.5 配置目录权限

* /conf/application.ini 配置文件，可读可写

* /install  安装目录，需要可读写

* /log      日志目录，需要可写

* /temp     缓存目录，需要可读写

## 2.6 直接访问安装

## 2.7 安装计划任务crontab模块,配置定时计划,用于定时发送邮件
<pre>
*/2 * * * * php -q /alidata/wwwroot/faka.zlkb.net/public/cli.php request_uri="/crontab/sendemail/index"
</pre> 	
	
# 三、系统升级
> 升级时，请先备份 /conf/application.ini 与 /install/install.lock 这二个文件;下载最新代码后直接覆盖，登录后台即可按照提示完成升级；

# 四、BUG与问题反馈
* 请联系我QQ:43036456
   
* 相关问题QQ交流群：701035212
   
# 五、推广QQ群
* 全国IDC行业精英群:572511758
   
* DirectAdmin用户交流群:337686498
   
