CREATE TABLE `ht_goods_comments` (
  `id`       INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `c_id`     INT(11)                   DEFAULT NULL,
  `goods_id` INT(11)                   DEFAULT NULL,
  `content`  TEXT,
  `ctime`    INT(10) UNSIGNED          DEFAULT NULL,
  `vtime`    INT(11)                   DEFAULT NULL,
  `reply`    TEXT
             CHARACTER SET utf8
             COLLATE utf8_bin,
  `status`   TINYINT(4)                DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8
  ROW_FORMAT = COMPACT;


INSERT INTO `ht_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `status`)
VALUES
  (281, '商品留言查看', 215, 0, 'Comments/lists', 0, '', '商品留言', 0, 1),
  (283, '编辑/回复留言', 281, 0, 'Comments/edit', 1, '', '', 0, 1),
  (284, '删除留言', 281, 0, 'Comments/del', 1, '', '', 0, 1);




