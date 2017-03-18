CREATE DATABASE IF NOT EXISTS `issues` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `issues`;

CREATE TABLE IF NOT EXISTS `issue_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_id` int(11) NOT NULL,
  `member_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message` TEXT COLLATE utf8_unicode_ci NOT NULL,
  `status` ENUM('new','published','deleted','archived') NOT NULL DEFAULT 'new' COLLATE 'utf8_unicode_ci',
  `helpful` int(11) NOT NULL DEFAULT 0,
  `not_helpful` int(11) NOT NULL DEFAULT 0,
  `last_updated` DATETIME NOT NULL,
  `date_added` DATETIME NOT NULL,

  PRIMARY KEY (`comment_id`),
  INDEX `issue_id` (`issue_id`),
  INDEX `member_id` (`member_id`),
  INDEX `status` (`status`),
  INDEX `helpful` (`helpful`),
  INDEX `not_helpful` (`not_helpful`),
  INDEX `last_updated` (`last_updated`),
  INDEX `date_added` (`date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `issue` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` TEXT COLLATE utf8_unicode_ci NOT NULL,
  `class_id` tinyint(2) DEFAULT NULL,
  `category_id` tinyint(2) DEFAULT NULL,
  `object_id` tinyint(3) DEFAULT NULL,
  `subject_id` tinyint(3) DEFAULT NULL,
  `priority` ENUM('1','2','3','4','5') NOT NULL DEFAULT '3' COLLATE 'utf8_unicode_ci',
  `status` ENUM('new','opened','closed','deleted','archived') NOT NULL DEFAULT 'new' COLLATE 'utf8_unicode_ci',
  `helpful` int(11) NOT NULL DEFAULT 0,
  `not_helpful` int(11) NOT NULL DEFAULT 0,
  `comments_amount` int(11) NOT NULL DEFAULT 0,
  `last_updated` DATETIME NOT NULL,
  `date_added` DATETIME NOT NULL,

  PRIMARY KEY (`issue_id`),	
	INDEX `member_id` (`member_id`),
	FULLTEXT INDEX `title` (`title`),	
	INDEX `class_id` (`class_id`),
	INDEX `category_id` (`category_id`),
	INDEX `object_id` (`object_id`),
	INDEX `subject_id` (`subject_id`),
	INDEX `priority` (`priority`),
	INDEX `status` (`status`),
	INDEX `helpful` (`helpful`),
    INDEX `not_helpful` (`not_helpful`),
	INDEX `comments_amount` (`comments_amount`),
	INDEX `last_updated` (`last_updated`),
	INDEX `date_added` (`date_added`)	

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `feedback_issue` (
  `issue_id` int(11) NOT NULL,
  `helpful` tinyint(1) NOT NULL,
  `member_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` DATETIME NOT NULL,

  INDEX `issue_id` (`issue_id`),
  INDEX `member_id` (`member_id`),
  INDEX `helpful` (`helpful`),
  INDEX `date_added` (`date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `feedback_issue_comment` (
  `comment_id` int(11) NOT NULL,
  `helpful` tinyint(1) NOT NULL,
  `member_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` DATETIME NOT NULL,

  INDEX `comment_id` (`comment_id`),
  INDEX `member_id` (`member_id`),
  INDEX `helpful` (`helpful`),
  INDEX `date_added` (`date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
