# zfaka ZFAKA发卡系统(本系统基于yaf+layui开发)

# 一、系统介绍
包含自动发卡功能，有会员中心和后台中心。
演示地址：http://faka.zlkb.net/

1.1 会员模块
> 默认情况下，不支持注册，当然后台可以开放注册；
>注册成会员可查看历史购买记录。
	
1.2 购买模块
>支持自动发卡和手工发卡模式；
>支付方式，目前只支持当面付，ps:因为我当前只有这个支付方式.
	

# 二、系统部署

2.1 需要安装yaf扩展,需要mysql支持

2.2 上传代码

2.3 配置权限
>/log      日志目录，需要可写
>/temp     缓存目录，需要可写
	
2.4 安装计划任务crontab模块
>配置定时计划,用于定时发送邮件
>参考：*/2 * * * * php -q /alidata/wwwroot/faka.zlkb.net/public/cli.php request_uri="/crontab/sendemail/index"
	
2.5 修改数据库链接
>/conf/application.ini
	
	
# 三、BUG与问题反馈
   请联系我QQ:43036456
