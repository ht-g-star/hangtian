INSERT INTO `ht_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `status`)
VALUES
  ('消费类别管理', 16, 0, 'ConsumeType/lists', 0, '', '用户管理', 0, 1);

INSERT INTO `ht_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `status`)
VALUES
  (279, '设置权限', 275, 0, 'ConsumeType/setAuth', 1, '', '', 0, 1),
  (278, '删除', 275, 0, 'ConsumeType/del', 1, '', '', 0, 1),
  (277, '修改', 275, 0, 'ConsumeType/edit', 1, '', '', 0, 1),
  (276, '增加', 275, 0, 'ConsumeType/add', 1, '', '', 0, 1);


CREATE TABLE `ht_consume_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `desc` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `ht_consume_type` (`id`, `name`, `desc`, `status`)
VALUES
  (1, X'E4B880E58FB7E6A5BC', X'E4B880E58FB7E6A5BCE79A84E6B688E8B4B90D0A', 1),
  (2, X'E7A59EE8889FE6A5BC', X'E7A59EE8889FE6A5BC', 1),
  (3, X'E4B880E9A490E58E85', X'E4B880E9A490E58E85', 1),
  (4, X'E5809AE6B996E8BDA9', X'E5809AE6B996E8BDA9', 1),
  (5, X'E581A5E5BAB7E7AEA1E79086E8B4B9', X'E581A5E5BAB7E7AEA1E79086E8B4B9', 1),
  (6, X'E59586E59381', X'E59586E59381', 1),
  (7, X'E694B6E993B6', X'E694B6E993B6', 1),
  (8, X'E890A5E99480', X'E890A5E99480', 1),
  (9, X'E8B4A2E58AA1', X'E8B4A2E58AA1', 1),
  (10, X'E4BC91E997B2E4B8ADE5BF83', X'E4BC91E997B2E4B8ADE5BF83', 1);

CREATE TABLE `ht_consume_type_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `type_ids` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE `ht_consume_log` ADD `type` VARCHAR(200)  NULL  DEFAULT NULL  AFTER `reason`;
