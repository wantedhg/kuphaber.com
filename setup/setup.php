<?php if(!defined('APP')) die('...'); ?>

CREATE TABLE IF NOT EXISTS `app_content` (
`content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`content_title` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT '',
`content_desc` blob,
`content_image` varchar(512) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
`content_image_local` varchar(128) COLLATE utf8mb4_turkish_ci DEFAULT '',
`content_link` varchar(256) COLLATE utf8mb4_turkish_ci DEFAULT '',
`content_source` varchar(32) COLLATE utf8mb4_turkish_ci DEFAULT '',
`content_time` timestamp NULL DEFAULT NULL,
`content_cat` int(1) DEFAULT '0',
`content_status` int(1) DEFAULT '0',
`content_twitter` int(1) DEFAULT '0',
`content_error` int(1) NOT NULL DEFAULT '0',
UNIQUE KEY `content_id` (`content_id`),
KEY `content_cat` (`content_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci
PARTITION BY HASH (content_id)
PARTITIONS 10;


ALTER TABLE `app_content` ADD `content_image_wh` VARCHAR(16) NULL AFTER `content_image_local`;

ALTER TABLE `app_content` CHANGE `content_image_local` `content_image_local` VARCHAR(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT '';
ALTER TABLE `app_content` CHANGE `content_title` `content_title` VARCHAR(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT '';
ALTER TABLE `app_content` CHANGE `content_link` `content_link` VARCHAR(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NULL DEFAULT '';

//tüm içerikleri aktif diye işaretleyelim
update `app_content` set content_status = 1;

//yeni içerikler daima aktif olsun
ALTER TABLE `app_content` CHANGE `content_status` `content_status` INT(1) NULL DEFAULT '1';
