# ZFAKA发卡系统(本系统基于yaf+layui开发)


# 一、系统介绍
包含自动发卡功能，有会员中心和后台中心。
演示地址：http://faka.zlkb.net/

1.1 会员模块
* 默认情况下，不支持注册，当然后台可以开放注册；

* 注册成会员可查看历史购买记录。
	
1.2 购买模块
* 支持自动发卡和手工发卡模式；

	
1.3 补充关于支付渠道问题
* 支付方式：支付宝当面付，码支付（支付宝扫码支付）

* 不要问我可不可以支持，只要你提供测试账户与接口文档，一般1－2周我都会更新上去；


# 二、系统部署

2.1 环境安装，推荐使用lnmp套件

>参考：https://zlkb.net/241.html

>相关配置
* nginx下root的配置root路径一定要加上public目录. 参考:/alidata/wwwroot/faka.zlkb.net/public;

* nginx下rewrite规则
<pre>      location / {
                if (!-e $request_filename) {
                        rewrite ^/(.*)$ /index.php?$1 last;
                }
        }
</pre> 

* nginx下rewrite规则(如果上面的不行，就用这个，有一个问号的差别)
<pre>      location / {
                if (!-e $request_filename) {
                        rewrite ^/(.*)$ /index.php$1 last;
                }
        }
</pre> 

2.2 需要安装yaf扩展,需要mysql支持
>参考：https://zlkb.net/243.html

>补充：php.ini中一定要配置 yaf.use_namespace=1


<pre>#####################################################</pre> 

## 特别补充说明：yaf的环境安装比较麻烦，需要注意一些问题；

* 安装时yaf版本注意事项 https://zlkb.net/435.html

* 配置nginx vhost中root路径一定要加上public目录，例如:  /alidata/wwwroot/faka.zlkb.net/public;

* 配置nginx vhost中一定要添加rewrite规则,参考上面

* 一定要取消 防跨站攻击(open_basedir),宝塔面板中直接取消勾即可，lnmp的环境，直接可以运行tools/remove_open_basedir_restriction.sh

* 项目运行给站点用户权限，相关目录权限参考安装引导

* mysql数据库配置时建议这样操作参考：https://zlkb.net/302.html

* 预留一下，我实在想不到还有什么要注意的了

<pre>#######################################################</pre> 



2.3 下载代码
<pre>
git clone https://github.com/zlkbdotnet/zfaka.git
</pre> 

2.4 配置目录权限

* /conf/application.ini 配置文件，可读可写

* /install  安装目录，需要可读写

* /log      日志目录，需要可写

* /temp     缓存目录，需要可读写

2.5 直接访问安装

2.6 安装计划任务crontab模块,配置定时计划,用于定时发送邮件
<pre>
*/2 * * * * php -q /alidata/wwwroot/faka.zlkb.net/public/cli.php request_uri="/crontab/sendemail/index"
</pre> 	
	
# 三、BUG与问题反馈
   请联系我QQ:43036456
   
   相关问题QQ交流群：701035212
   
# 四、推广QQ群
   全国IDC行业精英群:572511758
   
   DirectAdmin用户交流群:337686498
   
