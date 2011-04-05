-- ----------------------------
-- Table structure for forum_categories
-- ----------------------------
DROP TABLE IF EXISTS `forum_categories`;
CREATE TABLE `forum_categories` (
  `id` mediumint(5) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci NOT NULL,
  `topics` mediumint(8) NOT NULL,
  `posts` mediumint(8) NOT NULL,
  `order` tinyint(2) NOT NULL,
  `last_topic_id` mediumint(8) NOT NULL,
  `last_topic_subject` varchar(150) collate utf8_unicode_ci NOT NULL,
  `last_topic_created` datetime NOT NULL,
  `last_topic_user_id` mediumint(8) NOT NULL,
  `last_topic_username` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for forum_posts
-- ----------------------------
DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE `forum_posts` (
  `id` mediumint(8) NOT NULL auto_increment,
  `forum_category_id` mediumint(8) NOT NULL,
  `forum_topic_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `text` mediumtext collate utf8_unicode_ci NOT NULL,
  `topic` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cat_id` (`forum_category_id`),
  KEY `topic_id` (`forum_topic_id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `s` (`text`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for forum_topics
-- ----------------------------
DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE `forum_topics` (
  `id` mediumint(8) NOT NULL auto_increment,
  `user_id` mediumint(8) NOT NULL,
  `forum_category_id` mediumint(8) NOT NULL,
  `subject` varchar(150) collate utf8_unicode_ci NOT NULL,
  `views` mediumint(8) NOT NULL,
  `replies` mediumint(8) NOT NULL,
  `created` datetime NOT NULL,
  `last_post_id` mediumint(8) NOT NULL,
  `last_post_page` mediumint(3) NOT NULL,
  `last_post_created` datetime NOT NULL,
  `last_post_user_id` mediumint(8) NOT NULL,
  `last_post_username` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cat_id` (`forum_category_id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `subject` (`subject`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(7) unsigned NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `description` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) collate utf8_unicode_ci NOT NULL,
  `data` text collate utf8_unicode_ci NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` smallint(8) NOT NULL auto_increment,
  `username` varchar(64) collate utf8_unicode_ci NOT NULL,
  `password` varchar(64) collate utf8_unicode_ci NOT NULL,
  `email` varchar(64) collate utf8_unicode_ci NOT NULL,
  `group_id` smallint(6) NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  `confirm` char(40) collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uc` (`username`,`confirm`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;