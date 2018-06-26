-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-06-26 17:31:27
-- 服务器版本： 5.5.56-log
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `faka`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_admin_login_log`
--

CREATE TABLE IF NOT EXISTS `t_admin_login_log` (
  `id` int(11) NOT NULL,
  `adminid` int(11) NOT NULL COMMENT '管理员id',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录ip',
  `addtime` int(11) NOT NULL COMMENT '登录时间'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='管理员登录日志';

--
-- 转存表中的数据 `t_admin_login_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `t_admin_user`
--

CREATE TABLE IF NOT EXISTS `t_admin_user` (
  `id` int(11) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `secret` varchar(55) NOT NULL,
  `updatetime` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_admin_user`
--

INSERT INTO `t_admin_user` (`id`, `email`, `password`, `secret`, `updatetime`) VALUES
(1, '43036456@qq.com', '395e7618c964f60bcbb21afa65fe28f2', 'b830d8', 0);

-- --------------------------------------------------------

--
-- 表的结构 `t_config`
--

CREATE TABLE IF NOT EXISTS `t_config` (
  `id` int(11) NOT NULL,
  `catid` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(32) NOT NULL COMMENT '配置名',
  `value` text NOT NULL COMMENT '配置内容',
  `tag` text NOT NULL COMMENT '备注',
  `lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '锁',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '最后修改时间'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='基础配置';

--
-- 转存表中的数据 `t_config`
--

INSERT INTO `t_config` (`id`, `catid`, `name`, `value`, `tag`, `lock`, `updatetime`) VALUES
(1, 1, 'is_open_register', '0', '是否开放注册功能,1是开放,0是关闭', 1, 1453452674),
(2, 1, 'limit_ip_order', '3', '同一ip下单限制（针对未付款订单）', 1, 1453452674),
(3, 1, 'limit_email_order', '3', '同一email下单限制（针对未付款订单）', 1, 1453452674),
(4, 1, 'web_url', 'http://faka.zlkb.net', '当前网站地址', 1, 1453452674),
(5, 1, 'admin_email', '43036456@qq.com', '管理员邮箱', 1, 1453452674);

-- --------------------------------------------------------

--
-- 表的结构 `t_config_cat`
--

CREATE TABLE IF NOT EXISTS `t_config_cat` (
  `id` int(11) NOT NULL,
  `catname` varchar(32) NOT NULL COMMENT '配置分类名',
  `key` varchar(32) NOT NULL COMMENT '配置分类KEY'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='基础配置分类';

--
-- 转存表中的数据 `t_config_cat`
--

INSERT INTO `t_config_cat` (`id`, `catname`, `key`) VALUES
(1, '基础设置', 'basic'),
(2, '其他设置', 'other');

-- --------------------------------------------------------

--
-- 表的结构 `t_email`
--

CREATE TABLE IF NOT EXISTS `t_email` (
  `id` int(11) NOT NULL,
  `mailaddress` varchar(55) NOT NULL COMMENT '邮箱地址',
  `mailpassword` varchar(255) NOT NULL COMMENT '邮箱密码',
  `sendmail` varchar(55) NOT NULL COMMENT '	发件人email',
  `sendname` varchar(55) NOT NULL COMMENT '发送人昵称',
  `port` varchar(55) NOT NULL COMMENT '端口号',
  `host` varchar(55) NOT NULL COMMENT '发送邮件服务端'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_email`
--

INSERT INTO `t_email` (`id`, `mailaddress`, `mailpassword`, `sendmail`, `sendname`, `port`, `host`) VALUES
(1, 'noreply@zlkb.net', 'Oneday0313', 'noreply@zlkb.net', '资料空白', '465', 'smtp.exmail.qq.com');

-- --------------------------------------------------------

--
-- 表的结构 `t_email_code`
--

CREATE TABLE IF NOT EXISTS `t_email_code` (
  `id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL COMMENT '操作类型',
  `userid` int(11) NOT NULL COMMENT '用户id',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `code` varchar(50) NOT NULL COMMENT '内容',
  `ip` varchar(50) NOT NULL COMMENT 'IP',
  `result` varchar(255) NOT NULL COMMENT '结果',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '结果0未发送 1已发送',
  `checkedStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未校验，1已校验'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `t_email_queue`
--

CREATE TABLE IF NOT EXISTS `t_email_queue` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT ' 收件人',
  `subject` varchar(55) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `sendtime` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `sendresult` text NOT NULL COMMENT '发送错误',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,未发送 ，1已发送，-1,失败'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_email_queue`
--


-- --------------------------------------------------------

--
-- 表的结构 `t_help`
--

CREATE TABLE IF NOT EXISTS `t_help` (
  `id` int(11) NOT NULL,
  `typeid` int(11) NOT NULL DEFAULT '1' COMMENT '类型',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `isactive` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1是激活，0是不激活',
  `addtime` int(11) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_help`
--

INSERT INTO `t_help` (`id`, `typeid`, `title`, `content`, `isactive`, `addtime`) VALUES
(1, 1, '这是什么系统', '这就是一个伟大的系统', 1, 1527775425);

-- --------------------------------------------------------

--
-- 表的结构 `t_order`
--

CREATE TABLE IF NOT EXISTS `t_order` (
  `id` int(11) NOT NULL,
  `orderid` varchar(55) NOT NULL COMMENT '订单号',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `email` varchar(55) NOT NULL COMMENT '邮箱',
  `pid` int(11) NOT NULL COMMENT '产品id',
  `productname` varchar(255) NOT NULL COMMENT '订单名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `chapwd` varchar(55) NOT NULL COMMENT '查询密码',
  `ip` varchar(55) NOT NULL COMMENT 'ip',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态0下单',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `tradeid` varchar(255) NOT NULL COMMENT '外部订单id',
  `paymethod` varchar(255) NOT NULL COMMENT '支付渠道',
  `paymoney` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付总金额',
  `kami` text NOT NULL COMMENT '卡密'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_order`

-- --------------------------------------------------------

--
-- 表的结构 `t_payment`
--

CREATE TABLE IF NOT EXISTS `t_payment` (
  `id` int(11) NOT NULL,
  `payment` varchar(55) NOT NULL COMMENT '支付名',
  `alias` varchar(55) NOT NULL COMMENT '别名',
  `sign_type` enum('RSA','RSA2','','') NOT NULL DEFAULT 'RSA2',
  `app_id` varchar(55) NOT NULL,
  `ali_public_key` text NOT NULL,
  `rsa_private_key` text NOT NULL,
  `notify_url` varchar(255) NOT NULL,
  `return_url` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未激活,1已激活'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_payment`
--

INSERT INTO `t_payment` (`id`, `payment`, `alias`, `sign_type`, `app_id`, `ali_public_key`, `rsa_private_key`, `notify_url`, `return_url`, `active`) VALUES
(1, '支付宝当面付', 'zfbf2f', 'RSA2', '2018060660307830', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnWbQH5nv0o+tgBPRX//b2iiBnxG59UwAXx9zvPfckPhgzP+WIKPa3b9cJOydJ4/C6xWpaNega4EdV4NNxqxWy1mRjQQuG6MI5JbDThDxyZvItOtnonq4x/czrZaf3jTqizhg32kap+WQtU2Ab/cF01RnsxMiqmF/bg3wBifoKcav2WqEeSbEkKR0SKixJzK0YAGWmvT3zUd63zmnunZ4jfTDHM0h+0nM57oY80vVrhBQOC0spzrBwEgC/vAJJ3Eu2Odb1ewSXJsd0Y6xw2Z2+nkSYcnmRQctFTOHFuUskKC8q6TDtsaFFu1QYIJReapkUClaZkx27JILEULkmzpO0QIDAQAB', 'MIIEowIBAAKCAQEA47Hls/3gyOGToOeeKMIyvt8lJqIQNBI2+IT14DHwNUizrBvnOH5YPSBpBez+IrF9HQg/JgpGsHNaJRuw9IGwFSTJTg3w5r6fq15F1Pv9WAxMysiiO+euSxA/jYko1CRZvfL7lvkJRXR5e/esTHau12lCDDzjkYwNQ2MUja4dU8xQsdxwoGtyHXJWJqdJZCnTv6pjTMCmrtyl1YgocnMHrDiKxJZPdD9otM/UUUxqH7LHxzCsGcMWOX2vPIY3mip7mcm8/Cbv9x9j1MFqSQsAPnAQpvl5AqCskssqXI1+AFJV1TczSRtcId/8D1AOz0N3Jp3p6KLUvIeckx0IKcsL8QIDAQABAoIBAHAMrIht6D+S2q51LNzL7GMHtuWTHTwytoCIFeN1T8s48spAlQG1E0FJgMVcwhdalsJBu6nBptXQfk0CmotIgtl01+ekbqduqW6QnvbX5u18aBHmuAeY1Km13kCNfd0f4lXajDrYZNqcHeiGknCyewogj1MsZ6TJcgF2cbfjNiV02Wu0t2VqiOYvhL3jxZmuqtmohUJuD+UlVGuRyAfxuCtyY7Kwwg9aHiHmSPKBMlkvz45D4oIeh/St0iczbkjFEmgBhJbaTvY6kb+ftDds4SMlO+G8AHeoVxCTK2n0EPQrMJBv3yw62PDd44Yi7Ocy9n7uP5DBIchjxfU/QtTrOj0CgYEA/C6p4J+DIScmHZ7e4WDxYg4AmL5MqHKYWF6W6giSme4xwS0IExNF/uMG7gayZnVCN/TMWfKIz/vffNzmgfElg38uEnC2pzjRA85FUwWVAWQf9pFDsXglUFYV03Irr4xdOKYJY3kmi75c/dbfQZMwaKk9SPImZXwbFYywu74ZLzsCgYEA5yRVH2PJ1z01ziM9RxVO95ne82+rAco1KSyQbCUjSY+N90wAJv5g0oaqqHM938zPEAgWUYzIYhOhDDl+iLD8b0fQaGF069gBG/1ME6acRlcA/oJk/tiLCh81uX5MSbljQpsSuzaNKWOPiBU5qnVctpqdOr2ZRKNCnxF+lBjXFsMCgYBAQrPPJVaI0C0KRz2MOOPJlL/aLEPkeK8RdakHIgAZcXUEQLFaCwM/HCLb7KDxMtgY4ixgj0kFlq0waCnk2sowY+wdjtM8cWHxklJgAYAixpzOe93AD1mSyxzssJ8pMJ9gnA6wZmLIIU4Y8PQbBaLESLvxJqNaIPHV3+/5J0vLmQKBgD6YZET/7SVQ+kU83ddmOeBr1yjt0M3HSOZBn0rqM8g0DCowdsoYkXfx6D1+F/+Ho3uf9QtYqNuGnVKGL3ItG++27wWVfuhvsr01t2iK8j9KSIakIV2yntS65v246kf84JilDkmM2GWhBLnBpcA30EfTT0kdF2izHdOeFT1eDMGpAoGBAPe3jXHwM2yllMZbzv7vUpa4zi7SpYj+pgQmys332dewawuT91lAiNiLM+HKQ5/6fBX/QM/WE7kEKGzMO811rb76QfemcMRFe3khFIshfoRoqJCEFMK9onLZef4uuDHA3FCZvI4bT7jwdKF4Kn6JKo1UpvIpTuXAQTFGP6zNkAf8', '/product/notify/', '/product/query/', 1);

-- --------------------------------------------------------

--
-- 表的结构 `t_products`
--

CREATE TABLE IF NOT EXISTS `t_products` (
  `id` int(11) NOT NULL,
  `typeid` int(11) NOT NULL COMMENT '类型id',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未激活 1激活',
  `name` varchar(55) NOT NULL COMMENT '产品名',
  `description` text NOT NULL COMMENT '描述',
  `stockcontrol` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不控制,1控制',
  `qty` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `auto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0手动,1自动',
  `sort_num` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_products`
--

INSERT INTO `t_products` (`id`, `typeid`, `active`, `name`, `description`, `stockcontrol`, `qty`, `price`, `auto`, `sort_num`, `addtime`) VALUES
(1, 1, 1, '测试商品', '测试使用', 0, 0, '0.10', 1, 99, 1528962221);

-- --------------------------------------------------------

--
-- 表的结构 `t_products_card`
--

CREATE TABLE IF NOT EXISTS `t_products_card` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `card` text NOT NULL COMMENT '卡密',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0可用 1已使用'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_products_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `t_products_type`
--

CREATE TABLE IF NOT EXISTS `t_products_type` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL COMMENT '类型命名',
  `sort_num` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未激活,1已激活'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_products_type`
--

INSERT INTO `t_products_type` (`id`, `name`, `sort_num`, `active`) VALUES
(1, '测试商品', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `t_seo`
--

CREATE TABLE IF NOT EXISTS `t_seo` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `t_ticket`
--

CREATE TABLE IF NOT EXISTS `t_ticket` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `typeid` int(11) NOT NULL COMMENT '类型',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不重要 1重要',
  `subject` varchar(255) NOT NULL COMMENT '主题',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,刚创建;1,已回复;5已完结',
  `addtime` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_ticket`
--


-- --------------------------------------------------------

--
-- 表的结构 `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `id` int(11) NOT NULL,
  `groupid` int(11) NOT NULL DEFAULT '1' COMMENT '分组ID',
  `nickname` varchar(20) NOT NULL COMMENT '用户名',
  `password` text NOT NULL COMMENT '密码',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `qq` varchar(20) NOT NULL COMMENT 'qq',
  `mobilephone` varchar(15) NOT NULL COMMENT '手机',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `tag` varchar(255) NOT NULL COMMENT '用户自己的备注',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_user`
--

INSERT INTO `t_user` (`id`, `groupid`, `nickname`, `password`, `email`, `qq`, `mobilephone`, `money`, `integral`, `tag`, `createtime`) VALUES
(1, 1, '测试账户', 'e10adc3949ba59abbe56e057f20f883e', '43036456@qq.com', '43036456', '13717335559', '0.00', 0, '资料空白是大帅锅', 1525857488);

-- --------------------------------------------------------

--
-- 表的结构 `t_user_group`
--

CREATE TABLE IF NOT EXISTS `t_user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组名',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_user_group`
--

INSERT INTO `t_user_group` (`id`, `name`, `remark`, `discount`) VALUES
(1, '普通', '普通用户', '0.00'),
(2, 'VIP1', 'VIP1用户', '0.00'),
(3, 'VIP2', 'VIP2用户', '0.00'),
(4, 'VIP3', 'VIP3用户', '0.00');

-- --------------------------------------------------------

--
-- 表的结构 `t_user_login_logs`
--

CREATE TABLE IF NOT EXISTS `t_user_login_logs` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `ip` varchar(25) NOT NULL COMMENT '登录ip',
  `addtime` int(11) NOT NULL COMMENT '登录时间'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `t_user_login_logs`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_admin_login_log`
--
ALTER TABLE `t_admin_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_admin_user`
--
ALTER TABLE `t_admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_config`
--
ALTER TABLE `t_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_config_cat`
--
ALTER TABLE `t_config_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_email`
--
ALTER TABLE `t_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_email_code`
--
ALTER TABLE `t_email_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_email_queue`
--
ALTER TABLE `t_email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_help`
--
ALTER TABLE `t_help`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_order`
--
ALTER TABLE `t_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_payment`
--
ALTER TABLE `t_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_products`
--
ALTER TABLE `t_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_products_card`
--
ALTER TABLE `t_products_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_products_type`
--
ALTER TABLE `t_products_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_seo`
--
ALTER TABLE `t_seo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_ticket`
--
ALTER TABLE `t_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_user_group`
--
ALTER TABLE `t_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_user_login_logs`
--
ALTER TABLE `t_user_login_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_admin_login_log`
--
ALTER TABLE `t_admin_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `t_admin_user`
--
ALTER TABLE `t_admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_config`
--
ALTER TABLE `t_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `t_config_cat`
--
ALTER TABLE `t_config_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `t_email`
--
ALTER TABLE `t_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_email_code`
--
ALTER TABLE `t_email_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_email_queue`
--
ALTER TABLE `t_email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `t_help`
--
ALTER TABLE `t_help`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_order`
--
ALTER TABLE `t_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `t_payment`
--
ALTER TABLE `t_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_products`
--
ALTER TABLE `t_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `t_products_card`
--
ALTER TABLE `t_products_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `t_products_type`
--
ALTER TABLE `t_products_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `t_seo`
--
ALTER TABLE `t_seo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t_ticket`
--
ALTER TABLE `t_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_user_group`
--
ALTER TABLE `t_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `t_user_login_logs`
--
ALTER TABLE `t_user_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
