ALTER TABLE `t_products` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';
ALTER TABLE `t_products_card` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';
ALTER TABLE `t_products_type` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';