INSERT INTO `t_config` (`id`, `catid`, `name`, `value`, `tag`, `lock`, `updatetime`) VALUES (33, 1, 'tpl', 'hyacinth', '全新的整站模版', '1', '1546063186');
DELETE FROM `t_config` WHERE `t_config`.`id` = 29;
DELETE FROM `t_config` WHERE `t_config`.`id` = 19;
DELETE FROM `t_config` WHERE `t_config`.`id` = 12;
ALTER TABLE `t_products` ADD `price_ori` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价' AFTER `price`;