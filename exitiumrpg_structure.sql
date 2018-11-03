-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Client :  mysql51-94.bdb
-- Généré le :  Ven 12 Juin 2015 à 23:11
-- Version du serveur :  5.1.73-2+squeeze+build1+1-log
-- Version de PHP :  5.3.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `exitiumrpg`
--
CREATE DATABASE IF NOT EXISTS `exitiumrpg` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `exitiumrpg`;

-- --------------------------------------------------------

--
-- Structure de la table `ajax_chat_bans`
--

CREATE TABLE IF NOT EXISTS `ajax_chat_bans` (
  `userID` int(11) NOT NULL,
  `userName` varchar(64) COLLATE utf8_bin NOT NULL,
  `dateTime` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `userName` (`userName`),
  KEY `dateTime` (`dateTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `ajax_chat_invitations`
--

CREATE TABLE IF NOT EXISTS `ajax_chat_invitations` (
  `userID` int(11) NOT NULL,
  `channel` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  PRIMARY KEY (`userID`,`channel`),
  KEY `dateTime` (`dateTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `ajax_chat_messages`
--

CREATE TABLE IF NOT EXISTS `ajax_chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `userName` varchar(64) COLLATE utf8_bin NOT NULL,
  `userRole` int(1) NOT NULL,
  `channel` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `text` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  KEY `message_condition` (`id`,`channel`,`dateTime`),
  KEY `dateTime` (`dateTime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=77534 ;

-- --------------------------------------------------------

--
-- Structure de la table `ajax_chat_online`
--

CREATE TABLE IF NOT EXISTS `ajax_chat_online` (
  `userID` int(11) NOT NULL,
  `userName` varchar(64) COLLATE utf8_bin NOT NULL,
  `userRole` int(1) NOT NULL,
  `channel` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `userName` (`userName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_acl_groups`
--

CREATE TABLE IF NOT EXISTS `phpbb_acl_groups` (
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_setting` tinyint(2) NOT NULL DEFAULT '0',
  KEY `group_id` (`group_id`),
  KEY `auth_opt_id` (`auth_option_id`),
  KEY `auth_role_id` (`auth_role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_acl_options`
--

CREATE TABLE IF NOT EXISTS `phpbb_acl_options` (
  `auth_option_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `auth_option` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `is_global` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_local` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `founder_only` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`auth_option_id`),
  UNIQUE KEY `auth_option` (`auth_option`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=129 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_acl_roles`
--

CREATE TABLE IF NOT EXISTS `phpbb_acl_roles` (
  `role_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `role_description` text COLLATE utf8_bin NOT NULL,
  `role_type` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `role_order` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`),
  KEY `role_type` (`role_type`),
  KEY `role_order` (`role_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_acl_roles_data`
--

CREATE TABLE IF NOT EXISTS `phpbb_acl_roles_data` (
  `role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_setting` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`,`auth_option_id`),
  KEY `ath_op_id` (`auth_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_acl_users`
--

CREATE TABLE IF NOT EXISTS `phpbb_acl_users` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `auth_setting` tinyint(2) NOT NULL DEFAULT '0',
  KEY `user_id` (`user_id`),
  KEY `auth_option_id` (`auth_option_id`),
  KEY `auth_role_id` (`auth_role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_attachments`
--

CREATE TABLE IF NOT EXISTS `phpbb_attachments` (
  `attach_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `post_msg_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `in_message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `is_orphan` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `physical_filename` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `real_filename` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `download_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attach_comment` text COLLATE utf8_bin NOT NULL,
  `extension` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `mimetype` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `filesize` int(20) unsigned NOT NULL DEFAULT '0',
  `filetime` int(11) unsigned NOT NULL DEFAULT '0',
  `thumbnail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`attach_id`),
  KEY `filetime` (`filetime`),
  KEY `post_msg_id` (`post_msg_id`),
  KEY `topic_id` (`topic_id`),
  KEY `poster_id` (`poster_id`),
  KEY `is_orphan` (`is_orphan`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_banlist`
--

CREATE TABLE IF NOT EXISTS `phpbb_banlist` (
  `ban_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ban_userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ban_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ban_email` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ban_start` int(11) unsigned NOT NULL DEFAULT '0',
  `ban_end` int(11) unsigned NOT NULL DEFAULT '0',
  `ban_exclude` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ban_give_reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`ban_id`),
  KEY `ban_end` (`ban_end`),
  KEY `ban_user` (`ban_userid`,`ban_exclude`),
  KEY `ban_email` (`ban_email`,`ban_exclude`),
  KEY `ban_ip` (`ban_ip`,`ban_exclude`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_bbcodes`
--

CREATE TABLE IF NOT EXISTS `phpbb_bbcodes` (
  `bbcode_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `bbcode_tag` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_helpline` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `display_on_posting` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bbcode_match` text COLLATE utf8_bin NOT NULL,
  `bbcode_tpl` mediumtext COLLATE utf8_bin NOT NULL,
  `first_pass_match` mediumtext COLLATE utf8_bin NOT NULL,
  `first_pass_replace` mediumtext COLLATE utf8_bin NOT NULL,
  `second_pass_match` mediumtext COLLATE utf8_bin NOT NULL,
  `second_pass_replace` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`bbcode_id`),
  KEY `display_on_post` (`display_on_posting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_bookmarks`
--

CREATE TABLE IF NOT EXISTS `phpbb_bookmarks` (
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_bots`
--

CREATE TABLE IF NOT EXISTS `phpbb_bots` (
  `bot_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `bot_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `bot_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bot_agent` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bot_ip` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`bot_id`),
  KEY `bot_active` (`bot_active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_config`
--

CREATE TABLE IF NOT EXISTS `phpbb_config` (
  `config_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `config_value` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `is_dynamic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`config_name`),
  KEY `is_dynamic` (`is_dynamic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_confirm`
--

CREATE TABLE IF NOT EXISTS `phpbb_confirm` (
  `confirm_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `confirm_type` tinyint(3) NOT NULL DEFAULT '0',
  `code` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `seed` int(10) unsigned NOT NULL DEFAULT '0',
  `attempts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`,`confirm_id`),
  KEY `confirm_type` (`confirm_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_disallow`
--

CREATE TABLE IF NOT EXISTS `phpbb_disallow` (
  `disallow_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `disallow_username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`disallow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_drafts`
--

CREATE TABLE IF NOT EXISTS `phpbb_drafts` (
  `draft_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `save_time` int(11) unsigned NOT NULL DEFAULT '0',
  `draft_subject` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `draft_message` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`draft_id`),
  KEY `save_time` (`save_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_extensions`
--

CREATE TABLE IF NOT EXISTS `phpbb_extensions` (
  `extension_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `extension` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`extension_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_extension_groups`
--

CREATE TABLE IF NOT EXISTS `phpbb_extension_groups` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `cat_id` tinyint(2) NOT NULL DEFAULT '0',
  `allow_group` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `download_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `upload_icon` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `max_filesize` int(20) unsigned NOT NULL DEFAULT '0',
  `allowed_forums` text COLLATE utf8_bin NOT NULL,
  `allow_in_pm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_forums`
--

CREATE TABLE IF NOT EXISTS `phpbb_forums` (
  `forum_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `left_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_parents` mediumtext COLLATE utf8_bin NOT NULL,
  `forum_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_desc` text COLLATE utf8_bin NOT NULL,
  `forum_desc_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_desc_options` int(11) unsigned NOT NULL DEFAULT '7',
  `forum_desc_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_link` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_password` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_style` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_image` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_rules` text COLLATE utf8_bin NOT NULL,
  `forum_rules_link` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_rules_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_rules_options` int(11) unsigned NOT NULL DEFAULT '7',
  `forum_rules_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_topics_per_page` tinyint(4) NOT NULL DEFAULT '0',
  `forum_type` tinyint(4) NOT NULL DEFAULT '0',
  `forum_status` tinyint(4) NOT NULL DEFAULT '0',
  `forum_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_topics_real` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_last_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_last_post_subject` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `forum_last_poster_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_last_poster_colour` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `forum_flags` tinyint(4) NOT NULL DEFAULT '32',
  `forum_options` int(20) unsigned NOT NULL DEFAULT '0',
  `display_subforum_list` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display_on_index` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_indexing` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_icons` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_prune` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `prune_next` int(11) unsigned NOT NULL DEFAULT '0',
  `prune_days` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prune_viewed` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prune_freq` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`forum_id`),
  KEY `left_right_id` (`left_id`,`right_id`),
  KEY `forum_lastpost_id` (`forum_last_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_forums_access`
--

CREATE TABLE IF NOT EXISTS `phpbb_forums_access` (
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `session_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`forum_id`,`user_id`,`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_forums_track`
--

CREATE TABLE IF NOT EXISTS `phpbb_forums_track` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mark_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_forums_watch`
--

CREATE TABLE IF NOT EXISTS `phpbb_forums_watch` (
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `notify_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  KEY `forum_id` (`forum_id`),
  KEY `user_id` (`user_id`),
  KEY `notify_stat` (`notify_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_groups`
--

CREATE TABLE IF NOT EXISTS `phpbb_groups` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_type` tinyint(4) NOT NULL DEFAULT '1',
  `group_founder_manage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_skip_auth` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `group_desc` text COLLATE utf8_bin NOT NULL,
  `group_desc_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `group_desc_options` int(11) unsigned NOT NULL DEFAULT '7',
  `group_desc_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `group_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_avatar` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `group_avatar_type` tinyint(2) NOT NULL DEFAULT '0',
  `group_avatar_width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `group_avatar_height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `group_rank` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_colour` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `group_sig_chars` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_receive_pm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_message_limit` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_max_recipients` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_legend` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_id`),
  KEY `group_legend_name` (`group_legend`,`group_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_icons`
--

CREATE TABLE IF NOT EXISTS `phpbb_icons` (
  `icons_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `icons_url` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `icons_width` tinyint(4) NOT NULL DEFAULT '0',
  `icons_height` tinyint(4) NOT NULL DEFAULT '0',
  `icons_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `display_on_posting` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`icons_id`),
  KEY `display_on_posting` (`display_on_posting`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_lang`
--

CREATE TABLE IF NOT EXISTS `phpbb_lang` (
  `lang_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `lang_iso` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lang_dir` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lang_english_name` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lang_local_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lang_author` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`lang_id`),
  KEY `lang_iso` (`lang_iso`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_log`
--

CREATE TABLE IF NOT EXISTS `phpbb_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `reportee_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `log_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `log_time` int(11) unsigned NOT NULL DEFAULT '0',
  `log_operation` text COLLATE utf8_bin NOT NULL,
  `log_data` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_type` (`log_type`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_id` (`topic_id`),
  KEY `reportee_id` (`reportee_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1987 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_login_attempts`
--

CREATE TABLE IF NOT EXISTS `phpbb_login_attempts` (
  `attempt_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `attempt_browser` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
  `attempt_forwarded_for` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `attempt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `username_clean` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '0',
  KEY `att_ip` (`attempt_ip`,`attempt_time`),
  KEY `att_for` (`attempt_forwarded_for`,`attempt_time`),
  KEY `att_time` (`attempt_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_mchat`
--

CREATE TABLE IF NOT EXISTS `phpbb_mchat` (
  `message_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message` mediumtext COLLATE utf8_bin NOT NULL,
  `bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_options` tinyint(1) unsigned NOT NULL DEFAULT '7',
  `message_time` int(11) NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_mchat_config`
--

CREATE TABLE IF NOT EXISTS `phpbb_mchat_config` (
  `config_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `config_value` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_mchat_sessions`
--

CREATE TABLE IF NOT EXISTS `phpbb_mchat_sessions` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_lastupdate` int(11) unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_moderator_cache`
--

CREATE TABLE IF NOT EXISTS `phpbb_moderator_cache` (
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `display_on_index` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `disp_idx` (`display_on_index`),
  KEY `forum_id` (`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_modules`
--

CREATE TABLE IF NOT EXISTS `phpbb_modules` (
  `module_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `module_display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `module_basename` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `module_class` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `left_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `right_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module_langname` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `module_mode` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `module_auth` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`module_id`),
  KEY `left_right_id` (`left_id`,`right_id`),
  KEY `module_enabled` (`module_enabled`),
  KEY `class_left_id` (`module_class`,`left_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=204 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_poll_options`
--

CREATE TABLE IF NOT EXISTS `phpbb_poll_options` (
  `poll_option_id` tinyint(4) NOT NULL DEFAULT '0',
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `poll_option_text` text COLLATE utf8_bin NOT NULL,
  `poll_option_total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  KEY `poll_opt_id` (`poll_option_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_poll_votes`
--

CREATE TABLE IF NOT EXISTS `phpbb_poll_votes` (
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `poll_option_id` tinyint(4) NOT NULL DEFAULT '0',
  `vote_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `vote_user_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  KEY `topic_id` (`topic_id`),
  KEY `vote_user_id` (`vote_user_id`),
  KEY `vote_user_ip` (`vote_user_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_posts`
--

CREATE TABLE IF NOT EXISTS `phpbb_posts` (
  `post_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `icon_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `poster_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `post_approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `post_reported` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_bbcode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_magic_url` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_sig` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `post_username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `post_subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `post_text` mediumtext COLLATE utf8_bin NOT NULL,
  `post_checksum` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `post_attachment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `post_postcount` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `post_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  `post_edit_reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `post_edit_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `post_edit_count` smallint(4) unsigned NOT NULL DEFAULT '0',
  `post_edit_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_id` (`topic_id`),
  KEY `poster_ip` (`poster_ip`),
  KEY `poster_id` (`poster_id`),
  KEY `post_approved` (`post_approved`),
  KEY `post_username` (`post_username`),
  KEY `tid_post_time` (`topic_id`,`post_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1133 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_privmsgs`
--

CREATE TABLE IF NOT EXISTS `phpbb_privmsgs` (
  `msg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `root_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `icon_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message_time` int(11) unsigned NOT NULL DEFAULT '0',
  `enable_bbcode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_magic_url` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_sig` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `message_subject` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message_text` mediumtext COLLATE utf8_bin NOT NULL,
  `message_edit_reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message_edit_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message_attachment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  `message_edit_count` smallint(4) unsigned NOT NULL DEFAULT '0',
  `to_address` text COLLATE utf8_bin NOT NULL,
  `bcc_address` text COLLATE utf8_bin NOT NULL,
  `message_reported` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `author_ip` (`author_ip`),
  KEY `message_time` (`message_time`),
  KEY `author_id` (`author_id`),
  KEY `root_level` (`root_level`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2650 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_privmsgs_folder`
--

CREATE TABLE IF NOT EXISTS `phpbb_privmsgs_folder` (
  `folder_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `folder_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pm_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`folder_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_privmsgs_rules`
--

CREATE TABLE IF NOT EXISTS `phpbb_privmsgs_rules` (
  `rule_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rule_check` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rule_connection` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rule_string` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `rule_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rule_group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rule_action` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rule_folder_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rule_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_privmsgs_to`
--

CREATE TABLE IF NOT EXISTS `phpbb_privmsgs_to` (
  `msg_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pm_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pm_new` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pm_unread` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pm_replied` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pm_marked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pm_forwarded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `folder_id` int(11) NOT NULL DEFAULT '0',
  KEY `msg_id` (`msg_id`),
  KEY `author_id` (`author_id`),
  KEY `usr_flder_id` (`user_id`,`folder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_profile_fields`
--

CREATE TABLE IF NOT EXISTS `phpbb_profile_fields` (
  `field_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_type` tinyint(4) NOT NULL DEFAULT '0',
  `field_ident` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_length` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_minlen` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_maxlen` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_novalue` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_default_value` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_validation` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `field_required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_show_novalue` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_show_on_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_show_on_vt` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_show_profile` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_no_view` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`),
  KEY `fld_type` (`field_type`),
  KEY `fld_ordr` (`field_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_profile_fields_data`
--

CREATE TABLE IF NOT EXISTS `phpbb_profile_fields_data` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_profile_fields_lang`
--

CREATE TABLE IF NOT EXISTS `phpbb_profile_fields_lang` (
  `field_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lang_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `field_type` tinyint(4) NOT NULL DEFAULT '0',
  `lang_value` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`field_id`,`lang_id`,`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_profile_lang`
--

CREATE TABLE IF NOT EXISTS `phpbb_profile_lang` (
  `field_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lang_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lang_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lang_explain` text COLLATE utf8_bin NOT NULL,
  `lang_default_value` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`field_id`,`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_ranks`
--

CREATE TABLE IF NOT EXISTS `phpbb_ranks` (
  `rank_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rank_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `rank_min` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rank_special` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rank_image` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`rank_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_reports`
--

CREATE TABLE IF NOT EXISTS `phpbb_reports` (
  `report_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `reason_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pm_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_notify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `report_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `report_time` int(11) unsigned NOT NULL DEFAULT '0',
  `report_text` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `post_id` (`post_id`),
  KEY `pm_id` (`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_reports_reasons`
--

CREATE TABLE IF NOT EXISTS `phpbb_reports_reasons` (
  `reason_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `reason_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `reason_description` mediumtext COLLATE utf8_bin NOT NULL,
  `reason_order` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`reason_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_search_results`
--

CREATE TABLE IF NOT EXISTS `phpbb_search_results` (
  `search_key` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `search_time` int(11) unsigned NOT NULL DEFAULT '0',
  `search_keywords` mediumtext COLLATE utf8_bin NOT NULL,
  `search_authors` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`search_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_search_wordlist`
--

CREATE TABLE IF NOT EXISTS `phpbb_search_wordlist` (
  `word_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `word_text` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `word_common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `word_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`word_id`),
  UNIQUE KEY `wrd_txt` (`word_text`),
  KEY `wrd_cnt` (`word_count`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17691 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_search_wordmatch`
--

CREATE TABLE IF NOT EXISTS `phpbb_search_wordmatch` (
  `post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `word_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title_match` tinyint(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `unq_mtch` (`word_id`,`post_id`,`title_match`),
  KEY `word_id` (`word_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_sessions`
--

CREATE TABLE IF NOT EXISTS `phpbb_sessions` (
  `session_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `session_forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `session_last_visit` int(11) unsigned NOT NULL DEFAULT '0',
  `session_start` int(11) unsigned NOT NULL DEFAULT '0',
  `session_time` int(11) unsigned NOT NULL DEFAULT '0',
  `session_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_browser` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_forwarded_for` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_page` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `session_viewonline` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `session_autologin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `session_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`),
  KEY `session_time` (`session_time`),
  KEY `session_user_id` (`session_user_id`),
  KEY `session_fid` (`session_forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_sessions_keys`
--

CREATE TABLE IF NOT EXISTS `phpbb_sessions_keys` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`key_id`,`user_id`),
  KEY `last_login` (`last_login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_sitelist`
--

CREATE TABLE IF NOT EXISTS `phpbb_sitelist` (
  `site_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `site_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `site_hostname` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ip_exclude` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_smilies`
--

CREATE TABLE IF NOT EXISTS `phpbb_smilies` (
  `smiley_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `emotion` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `smiley_url` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `smiley_width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `smiley_height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `smiley_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `display_on_posting` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`smiley_id`),
  KEY `display_on_post` (`display_on_posting`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_styles`
--

CREATE TABLE IF NOT EXISTS `phpbb_styles` (
  `style_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `style_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `style_copyright` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `style_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `template_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `theme_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `imageset_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`style_id`),
  UNIQUE KEY `style_name` (`style_name`),
  KEY `template_id` (`template_id`),
  KEY `theme_id` (`theme_id`),
  KEY `imageset_id` (`imageset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_styles_imageset`
--

CREATE TABLE IF NOT EXISTS `phpbb_styles_imageset` (
  `imageset_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `imageset_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `imageset_copyright` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `imageset_path` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`imageset_id`),
  UNIQUE KEY `imgset_nm` (`imageset_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_styles_imageset_data`
--

CREATE TABLE IF NOT EXISTS `phpbb_styles_imageset_data` (
  `image_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `image_name` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `image_filename` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `image_lang` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `image_height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `image_width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `imageset_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `i_d` (`imageset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=282 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_styles_template`
--

CREATE TABLE IF NOT EXISTS `phpbb_styles_template` (
  `template_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `template_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `template_copyright` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `template_path` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'kNg=',
  `template_storedb` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `template_inherits_id` int(4) unsigned NOT NULL DEFAULT '0',
  `template_inherit_path` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `tmplte_nm` (`template_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_styles_template_data`
--

CREATE TABLE IF NOT EXISTS `phpbb_styles_template_data` (
  `template_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `template_filename` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `template_included` text COLLATE utf8_bin NOT NULL,
  `template_mtime` int(11) unsigned NOT NULL DEFAULT '0',
  `template_data` mediumtext COLLATE utf8_bin NOT NULL,
  KEY `tid` (`template_id`),
  KEY `tfn` (`template_filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_styles_theme`
--

CREATE TABLE IF NOT EXISTS `phpbb_styles_theme` (
  `theme_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `theme_copyright` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `theme_path` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `theme_storedb` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `theme_mtime` int(11) unsigned NOT NULL DEFAULT '0',
  `theme_data` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`theme_id`),
  UNIQUE KEY `theme_name` (`theme_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_topics`
--

CREATE TABLE IF NOT EXISTS `phpbb_topics` (
  `topic_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `icon_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_attachment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topic_approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `topic_reported` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topic_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `topic_poster` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_time` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_time_limit` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_replies_real` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_status` tinyint(3) NOT NULL DEFAULT '0',
  `topic_type` tinyint(3) NOT NULL DEFAULT '0',
  `topic_first_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_first_poster_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `topic_first_poster_colour` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `topic_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_last_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_last_poster_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `topic_last_poster_colour` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `topic_last_post_subject` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `topic_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_last_view_time` int(11) unsigned NOT NULL DEFAULT '0',
  `topic_moved_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_bumped` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topic_bumper` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `poll_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `poll_start` int(11) unsigned NOT NULL DEFAULT '0',
  `poll_length` int(11) unsigned NOT NULL DEFAULT '0',
  `poll_max_options` tinyint(4) NOT NULL DEFAULT '1',
  `poll_last_vote` int(11) unsigned NOT NULL DEFAULT '0',
  `poll_vote_change` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `forum_id_type` (`forum_id`,`topic_type`),
  KEY `last_post_time` (`topic_last_post_time`),
  KEY `topic_approved` (`topic_approved`),
  KEY `forum_appr_last` (`forum_id`,`topic_approved`,`topic_last_post_id`),
  KEY `fid_time_moved` (`forum_id`,`topic_last_post_time`,`topic_moved_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=170 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_topics_posted`
--

CREATE TABLE IF NOT EXISTS `phpbb_topics_posted` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_posted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_topics_track`
--

CREATE TABLE IF NOT EXISTS `phpbb_topics_track` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mark_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`topic_id`),
  KEY `topic_id` (`topic_id`),
  KEY `forum_id` (`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_topics_watch`
--

CREATE TABLE IF NOT EXISTS `phpbb_topics_watch` (
  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `notify_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `notify_stat` (`notify_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_users`
--

CREATE TABLE IF NOT EXISTS `phpbb_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(2) NOT NULL DEFAULT '0',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '3',
  `user_permissions` mediumtext COLLATE utf8_bin NOT NULL,
  `user_perm_from` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_regdate` int(11) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `username_clean` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_password` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_passchg` int(11) unsigned NOT NULL DEFAULT '0',
  `user_pass_convert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_email` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_email_hash` bigint(20) NOT NULL DEFAULT '0',
  `user_birthday` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
  `user_lastmark` int(11) unsigned NOT NULL DEFAULT '0',
  `user_lastpost_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_lastpage` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_last_confirm_key` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_last_search` int(11) unsigned NOT NULL DEFAULT '0',
  `user_warnings` tinyint(4) NOT NULL DEFAULT '0',
  `user_last_warning` int(11) unsigned NOT NULL DEFAULT '0',
  `user_login_attempts` tinyint(4) NOT NULL DEFAULT '0',
  `user_inactive_reason` tinyint(2) NOT NULL DEFAULT '0',
  `user_inactive_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_lang` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_timezone` decimal(5,2) NOT NULL DEFAULT '0.00',
  `user_dst` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_dateformat` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'd M Y H:i',
  `user_style` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_rank` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_colour` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_new_privmsg` int(4) NOT NULL DEFAULT '0',
  `user_unread_privmsg` int(4) NOT NULL DEFAULT '0',
  `user_last_privmsg` int(11) unsigned NOT NULL DEFAULT '0',
  `user_message_rules` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_full_folder` int(11) NOT NULL DEFAULT '-3',
  `user_emailtime` int(11) unsigned NOT NULL DEFAULT '0',
  `user_topic_show_days` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_topic_sortby_type` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 't',
  `user_topic_sortby_dir` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'd',
  `user_post_show_days` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_post_sortby_type` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 't',
  `user_post_sortby_dir` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'a',
  `user_notify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_notify_pm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_notify_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_allow_pm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_allow_viewonline` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_allow_viewemail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_allow_massemail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_options` int(11) unsigned NOT NULL DEFAULT '230271',
  `user_avatar` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_avatar_type` tinyint(2) NOT NULL DEFAULT '0',
  `user_avatar_width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_avatar_height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user_sig` mediumtext COLLATE utf8_bin NOT NULL,
  `user_sig_bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_sig_bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_from` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_icq` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_aim` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_yim` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_msnm` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_jabber` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_website` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_occ` text COLLATE utf8_bin NOT NULL,
  `user_interests` text COLLATE utf8_bin NOT NULL,
  `user_actkey` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_newpasswd` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_form_salt` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_new` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_reminded` tinyint(4) NOT NULL DEFAULT '0',
  `user_reminded_time` int(11) unsigned NOT NULL DEFAULT '0',
  `user_mchat_index` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_mchat_sound` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_mchat_stats_index` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_mchat_topics` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_mchat_avatars` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_clean` (`username_clean`),
  KEY `user_birthday` (`user_birthday`),
  KEY `user_email_hash` (`user_email_hash`),
  KEY `user_type` (`user_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_user_group`
--

CREATE TABLE IF NOT EXISTS `phpbb_user_group` (
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_leader` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_pending` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `group_leader` (`group_leader`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_warnings`
--

CREATE TABLE IF NOT EXISTS `phpbb_warnings` (
  `warning_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `log_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `warning_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`warning_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_words`
--

CREATE TABLE IF NOT EXISTS `phpbb_words` (
  `word_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `replacement` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`word_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `phpbb_zebra`
--

CREATE TABLE IF NOT EXISTS `phpbb_zebra` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `zebra_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `friend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `foe` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`zebra_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_achievements`
--

CREATE TABLE IF NOT EXISTS `rpg_achievements` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `condition_desc` varchar(200) NOT NULL,
  `category_id` mediumint(8) unsigned NOT NULL,
  `hide_condition` tinyint(1) NOT NULL DEFAULT '0',
  `script` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_achievements_categories`
--

CREATE TABLE IF NOT EXISTS `rpg_achievements_categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_active_quests`
--

CREATE TABLE IF NOT EXISTS `rpg_active_quests` (
  `quest_id` mediumint(8) unsigned NOT NULL,
  `player_id` mediumint(8) unsigned NOT NULL,
  `forum_id` mediumint(8) unsigned NOT NULL,
  `topic_id` mediumint(8) unsigned NOT NULL,
  `is_started` tinyint(1) NOT NULL DEFAULT '0',
  `is_opened` tinyint(1) NOT NULL DEFAULT '1',
  `riddle_id` mediumint(8) unsigned DEFAULT NULL,
  `battle_token` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`quest_id`,`player_id`),
  UNIQUE KEY `forum_id` (`forum_id`,`topic_id`),
  UNIQUE KEY `topic_id` (`topic_id`),
  UNIQUE KEY `battle_token` (`battle_token`),
  KEY `player_id` (`player_id`),
  KEY `riddle_id` (`riddle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_active_quests_members`
--

CREATE TABLE IF NOT EXISTS `rpg_active_quests_members` (
  `topic_id` mediumint(8) unsigned NOT NULL,
  `member_id` mediumint(8) unsigned NOT NULL,
  `answered_riddle` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`member_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_armor_parts`
--

CREATE TABLE IF NOT EXISTS `rpg_armor_parts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `type` varchar(20) COLLATE utf8_bin NOT NULL,
  `part_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `player_id_2` (`player_id`,`type`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=515 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_battle_areas`
--

CREATE TABLE IF NOT EXISTS `rpg_battle_areas` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `desc` varchar(200) COLLATE utf8_bin NOT NULL,
  `level` mediumint(8) unsigned NOT NULL,
  `bgm` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_battle_areas_monsters`
--

CREATE TABLE IF NOT EXISTS `rpg_battle_areas_monsters` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `area_part_id` mediumint(8) unsigned NOT NULL,
  `monster_id` mediumint(8) unsigned NOT NULL,
  `encounter_rate` float unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `area_part_id` (`area_part_id`,`monster_id`),
  KEY `monster_id` (`monster_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_battle_areas_parts`
--

CREATE TABLE IF NOT EXISTS `rpg_battle_areas_parts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `min_level` mediumint(8) unsigned NOT NULL,
  `max_level` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `area_id` (`area_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_blackmarket`
--

CREATE TABLE IF NOT EXISTS `rpg_blackmarket` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` mediumint(8) unsigned NOT NULL,
  `category` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'special',
  `place` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`,`place`),
  UNIQUE KEY `item_id` (`item_id`,`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_blackmarket_equips`
--

CREATE TABLE IF NOT EXISTS `rpg_blackmarket_equips` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` mediumint(8) unsigned NOT NULL,
  `type` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_id` (`item_id`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_clans`
--

CREATE TABLE IF NOT EXISTS `rpg_clans` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `desc` text COLLATE utf8_bin,
  `img` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `leader_id` mediumint(8) unsigned NOT NULL,
  `pi` int(10) unsigned NOT NULL DEFAULT '0',
  `atk_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `def_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `spd_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `flux_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `res_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pv_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pf_level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `leader_id` (`leader_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_clans_join_requests`
--

CREATE TABLE IF NOT EXISTS `rpg_clans_join_requests` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `clan_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`,`user_id`),
  UNIQUE KEY `user_id_2` (`user_id`,`clan_id`),
  KEY `clan_id` (`clan_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_clans_members`
--

CREATE TABLE IF NOT EXISTS `rpg_clans_members` (
  `clan_id` mediumint(8) unsigned NOT NULL,
  `member_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`clan_id`,`member_id`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_clans_messages`
--

CREATE TABLE IF NOT EXISTS `rpg_clans_messages` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `clan_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clan_id` (`clan_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_clothes`
--

CREATE TABLE IF NOT EXISTS `rpg_clothes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `price` int(8) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) NOT NULL DEFAULT '0',
  `pf` mediumint(8) NOT NULL DEFAULT '0',
  `atk` mediumint(8) NOT NULL DEFAULT '0',
  `def` mediumint(8) NOT NULL DEFAULT '0',
  `res` mediumint(8) NOT NULL DEFAULT '0',
  `vit` mediumint(8) NOT NULL DEFAULT '0',
  `flux` mediumint(8) NOT NULL DEFAULT '0',
  `img` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `req_lvl` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_event_battles`
--

CREATE TABLE IF NOT EXISTS `rpg_event_battles` (
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `monster_id` mediumint(8) unsigned NOT NULL,
  `monster_hp` int(10) unsigned NOT NULL,
  `monster_fp` int(10) unsigned NOT NULL,
  `bgm` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `forum_id` mediumint(8) unsigned NOT NULL,
  `topic_id` mediumint(8) unsigned NOT NULL,
  `can_register` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`token`),
  UNIQUE KEY `forum_id` (`forum_id`,`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_event_battles_items`
--

CREATE TABLE IF NOT EXISTS `rpg_event_battles_items` (
  `battle_token` varchar(200) COLLATE utf8_bin NOT NULL,
  `rank` smallint(5) unsigned NOT NULL,
  `item_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `item_id` mediumint(8) unsigned NOT NULL,
  `number` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`battle_token`,`rank`,`item_type`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_event_battles_players`
--

CREATE TABLE IF NOT EXISTS `rpg_event_battles_players` (
  `battle_token` varchar(200) COLLATE utf8_bin NOT NULL,
  `player_id` mediumint(8) unsigned NOT NULL,
  `in_event` tinyint(1) NOT NULL DEFAULT '0',
  `is_dead` tinyint(1) NOT NULL DEFAULT '0',
  `turn` int(10) unsigned NOT NULL DEFAULT '1',
  `player_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `monster_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `total_damage_given` int(10) unsigned NOT NULL DEFAULT '0',
  `total_damage_received` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`battle_token`,`player_id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_event_battles_registered_players`
--

CREATE TABLE IF NOT EXISTS `rpg_event_battles_registered_players` (
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `player_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`token`,`player_id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_gloves`
--

CREATE TABLE IF NOT EXISTS `rpg_gloves` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) NOT NULL DEFAULT '0',
  `pf` mediumint(8) NOT NULL DEFAULT '0',
  `atk` mediumint(8) NOT NULL DEFAULT '0',
  `def` mediumint(8) NOT NULL DEFAULT '0',
  `res` mediumint(8) NOT NULL DEFAULT '0',
  `vit` mediumint(8) NOT NULL DEFAULT '0',
  `flux` mediumint(8) NOT NULL DEFAULT '0',
  `img` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `req_lvl` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_inventories`
--

CREATE TABLE IF NOT EXISTS `rpg_inventories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` mediumint(8) unsigned NOT NULL,
  `slot` smallint(5) unsigned NOT NULL,
  `item_id` mediumint(8) unsigned NOT NULL,
  `item_type` varchar(30) COLLATE utf8_bin NOT NULL,
  `number` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `player_id` (`player_id`,`slot`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1662 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_karma_topics`
--

CREATE TABLE IF NOT EXISTS `rpg_karma_topics` (
  `forum_id` mediumint(8) unsigned NOT NULL,
  `topic_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`forum_id`,`topic_id`,`user_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_leggings`
--

CREATE TABLE IF NOT EXISTS `rpg_leggings` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `price` int(8) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) NOT NULL DEFAULT '0',
  `pf` mediumint(8) NOT NULL DEFAULT '0',
  `atk` mediumint(8) NOT NULL DEFAULT '0',
  `def` mediumint(8) NOT NULL DEFAULT '0',
  `res` mediumint(8) NOT NULL DEFAULT '0',
  `vit` mediumint(8) NOT NULL DEFAULT '0',
  `flux` mediumint(8) NOT NULL DEFAULT '0',
  `img` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `req_lvl` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_monsters`
--

CREATE TABLE IF NOT EXISTS `rpg_monsters` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(100) COLLATE utf8_bin NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `pv` int(10) unsigned NOT NULL,
  `pf` int(10) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL,
  `atk` int(11) NOT NULL,
  `def` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `spd` int(11) NOT NULL,
  `flux` int(11) NOT NULL,
  `bgm` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `behaviors` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT 'attack',
  `ralz` int(10) unsigned NOT NULL DEFAULT '0',
  `drops_number` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_monsters_drops`
--

CREATE TABLE IF NOT EXISTS `rpg_monsters_drops` (
  `monster_id` mediumint(8) unsigned NOT NULL,
  `area_part_id` mediumint(8) unsigned NOT NULL,
  `item_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `item_id` mediumint(8) unsigned NOT NULL,
  `rate` float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`monster_id`,`area_part_id`,`item_type`,`item_id`),
  KEY `item_id` (`item_id`),
  KEY `area_part_id` (`area_part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_monsters_skills`
--

CREATE TABLE IF NOT EXISTS `rpg_monsters_skills` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `skill_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `skill_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `skill_element` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  `monster_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `skill_type` (`skill_type`,`monster_id`),
  KEY `monster_id` (`monster_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_monster_books`
--

CREATE TABLE IF NOT EXISTS `rpg_monster_books` (
  `player_id` mediumint(8) unsigned NOT NULL,
  `monster_id` mediumint(8) unsigned NOT NULL,
  `area_part_id` mediumint(8) unsigned NOT NULL,
  `encounters` int(10) unsigned NOT NULL DEFAULT '0',
  `wins` int(10) unsigned NOT NULL DEFAULT '0',
  `loses` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`player_id`,`monster_id`,`area_part_id`),
  KEY `monster_id` (`monster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_orbs`
--

CREATE TABLE IF NOT EXISTS `rpg_orbs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `price` int(8) unsigned NOT NULL DEFAULT '0',
  `level` smallint(5) unsigned NOT NULL,
  `type` smallint(5) unsigned NOT NULL,
  `img` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `attack` float NOT NULL DEFAULT '0',
  `defense` float NOT NULL DEFAULT '0',
  `resistance` float NOT NULL DEFAULT '0',
  `speed` float NOT NULL DEFAULT '0',
  `flux` float NOT NULL DEFAULT '0',
  `pv` float NOT NULL DEFAULT '0',
  `pf` float NOT NULL DEFAULT '0',
  `effect` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `trig` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `size` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_organisations`
--

CREATE TABLE IF NOT EXISTS `rpg_organisations` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_players`
--

CREATE TABLE IF NOT EXISTS `rpg_players` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gender` varchar(1) COLLATE utf8_bin NOT NULL DEFAULT 'M',
  `level` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `leader` tinyint(1) NOT NULL,
  `pv` int(10) unsigned NOT NULL DEFAULT '100',
  `pf` int(10) unsigned NOT NULL DEFAULT '100',
  `xp` int(10) NOT NULL DEFAULT '0',
  `karma` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bgm` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `energy` mediumint(4) unsigned NOT NULL DEFAULT '0',
  `max_energy_bonus` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `inc_energy_bonus` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `total_battles` int(10) unsigned NOT NULL DEFAULT '0',
  `honor` mediumint(4) unsigned NOT NULL DEFAULT '0',
  `atk_points` smallint(8) unsigned NOT NULL DEFAULT '0',
  `def_points` smallint(8) unsigned NOT NULL DEFAULT '0',
  `spd_points` smallint(8) unsigned NOT NULL DEFAULT '0',
  `flux_points` smallint(8) unsigned NOT NULL DEFAULT '0',
  `res_points` smallint(8) unsigned NOT NULL DEFAULT '0',
  `ralz` int(10) unsigned NOT NULL,
  `orb1` mediumint(8) unsigned DEFAULT NULL,
  `orb2` mediumint(8) unsigned DEFAULT NULL,
  `orb3` mediumint(8) unsigned DEFAULT NULL,
  `orb4` mediumint(8) unsigned DEFAULT NULL,
  `organisation_id` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `clan_id` mediumint(8) unsigned DEFAULT NULL,
  `cloth_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `leggings_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `gloves_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `shoes_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `skill_1` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `skill_2` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `skill_3` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `skill_4` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `skill_1_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_2_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_3_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_4_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_1_element` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  `skill_2_element` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  `skill_3_element` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  `skill_4_element` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  `skill_1_subskill` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_2_subskill` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_3_subskill` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `skill_4_subskill` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `enable_sound` tinyint(1) NOT NULL DEFAULT '1',
  `enable_animations` tinyint(1) NOT NULL DEFAULT '1',
  `enable_alpha` tinyint(1) NOT NULL DEFAULT '1',
  `enable_hd` tinyint(1) NOT NULL DEFAULT '0',
  `introduction_link` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `salary_multiplier` float unsigned NOT NULL DEFAULT '1',
  `enable_salary_level_multiplier` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `orb1` (`orb1`),
  KEY `orb2` (`orb2`),
  KEY `orb3` (`orb3`),
  KEY `orb4` (`orb4`),
  KEY `organisation_id` (`organisation_id`),
  KEY `clan_id` (`clan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_players_achievements`
--

CREATE TABLE IF NOT EXISTS `rpg_players_achievements` (
  `player_id` mediumint(8) unsigned NOT NULL,
  `achievement_id` mediumint(8) unsigned NOT NULL,
  `unlocked` tinyint(1) NOT NULL,
  PRIMARY KEY (`player_id`,`achievement_id`),
  KEY `achievement_id` (`achievement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_players_stats`
--

CREATE TABLE IF NOT EXISTS `rpg_players_stats` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` mediumint(8) unsigned NOT NULL,
  `max_ralz_own` int(10) unsigned NOT NULL DEFAULT '0',
  `max_ralz_buy` int(10) unsigned NOT NULL DEFAULT '0',
  `max_ralz_send` int(10) unsigned NOT NULL DEFAULT '0',
  `pve_total_battles` int(10) unsigned NOT NULL DEFAULT '0',
  `pve_total_wins` int(10) unsigned NOT NULL DEFAULT '0',
  `pve_total_loses` int(10) unsigned NOT NULL DEFAULT '0',
  `pve_total_runs` int(10) unsigned NOT NULL DEFAULT '0',
  `pvp_total_battles` int(10) unsigned NOT NULL DEFAULT '0',
  `pvp_total_wins` int(10) unsigned NOT NULL DEFAULT '0',
  `pvp_total_loses` int(10) unsigned NOT NULL DEFAULT '0',
  `pvp_total_draws` int(10) unsigned NOT NULL DEFAULT '0',
  `war_total_wins` int(10) unsigned NOT NULL DEFAULT '0',
  `war_total_loses` int(10) unsigned NOT NULL DEFAULT '0',
  `karma_points` int(10) unsigned NOT NULL DEFAULT '0',
  `inn_times` int(10) unsigned NOT NULL DEFAULT '0',
  `buy_times` int(10) unsigned NOT NULL DEFAULT '0',
  `quests_number` int(10) unsigned NOT NULL DEFAULT '0',
  `event_times` int(10) unsigned NOT NULL DEFAULT '0',
  `event_best_rank` int(10) unsigned NOT NULL DEFAULT '0',
  `warehouse_max_slots` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `player_id` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_pve_battles`
--

CREATE TABLE IF NOT EXISTS `rpg_pve_battles` (
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `player_id` mediumint(8) unsigned NOT NULL,
  `monster_id` mediumint(8) unsigned NOT NULL,
  `monster_hp` int(10) unsigned NOT NULL,
  `monster_fp` int(10) unsigned NOT NULL DEFAULT '0',
  `turn` int(10) unsigned NOT NULL DEFAULT '1',
  `bgm` varchar(200) COLLATE utf8_bin NOT NULL,
  `player_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `monster_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `area_part_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`token`),
  UNIQUE KEY `player_id` (`player_id`),
  KEY `monster_id` (`monster_id`),
  KEY `area_part_id` (`area_part_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_pvp_battles`
--

CREATE TABLE IF NOT EXISTS `rpg_pvp_battles` (
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `player1_id` mediumint(8) unsigned NOT NULL,
  `player2_id` mediumint(8) unsigned NOT NULL,
  `player1_hp` int(10) unsigned NOT NULL,
  `player2_hp` int(10) unsigned NOT NULL,
  `player1_fp` int(10) unsigned NOT NULL,
  `player2_fp` int(10) unsigned NOT NULL,
  `turn` int(10) unsigned NOT NULL DEFAULT '1',
  `player1_bgm` varchar(200) COLLATE utf8_bin NOT NULL,
  `player2_bgm` varchar(200) COLLATE utf8_bin NOT NULL,
  `player1_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player2_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player1_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player2_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player1_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player2_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player1_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `player2_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `player1_in_battle` tinyint(1) NOT NULL DEFAULT '0',
  `player2_in_battle` tinyint(1) NOT NULL DEFAULT '0',
  `is_started` tinyint(1) NOT NULL DEFAULT '0',
  `is_over` tinyint(1) NOT NULL DEFAULT '0',
  `turn_time` int(10) unsigned DEFAULT NULL,
  `player1_last_active` int(10) unsigned NOT NULL DEFAULT '1',
  `player2_last_active` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`token`),
  UNIQUE KEY `user1_id` (`player1_id`),
  UNIQUE KEY `user2_id` (`player2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_pvp_battles_actions`
--

CREATE TABLE IF NOT EXISTS `rpg_pvp_battles_actions` (
  `player_id` mediumint(8) unsigned NOT NULL,
  `turn` mediumint(8) unsigned NOT NULL,
  `action` varchar(20) COLLATE utf8_bin NOT NULL,
  `skill_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `skill_slot` mediumint(8) unsigned DEFAULT NULL,
  `battle_token` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`player_id`,`turn`),
  KEY `battle_token` (`battle_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_pvp_battles_requests`
--

CREATE TABLE IF NOT EXISTS `rpg_pvp_battles_requests` (
  `token` varchar(100) COLLATE utf8_bin NOT NULL,
  `user1_id` mediumint(8) unsigned NOT NULL,
  `user2_id` mediumint(8) unsigned NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL,
  `topic_id` mediumint(8) unsigned NOT NULL,
  `battle_token` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`token`),
  UNIQUE KEY `user2_id` (`user2_id`),
  UNIQUE KEY `user1_id` (`user1_id`),
  UNIQUE KEY `battle_token` (`battle_token`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_pvp_battles_turn_results`
--

CREATE TABLE IF NOT EXISTS `rpg_pvp_battles_turn_results` (
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `turn` mediumint(8) unsigned NOT NULL,
  `result` text COLLATE utf8_bin NOT NULL,
  `player1_read` tinyint(1) NOT NULL DEFAULT '0',
  `player2_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`,`turn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_pvp_forums`
--

CREATE TABLE IF NOT EXISTS `rpg_pvp_forums` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_quests`
--

CREATE TABLE IF NOT EXISTS `rpg_quests` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_bin NOT NULL,
  `descr` text COLLATE utf8_bin,
  `type` varchar(100) COLLATE utf8_bin NOT NULL,
  `date` int(11) NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `is_unique` tinyint(1) NOT NULL DEFAULT '0',
  `posts_number` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `forum_id` mediumint(8) unsigned NOT NULL,
  `monster_id` mediumint(8) unsigned DEFAULT NULL,
  `bgm` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `xp` int(10) unsigned NOT NULL,
  `ralz` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `forum_id` (`forum_id`),
  KEY `monster_id` (`monster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_quests_rewards`
--

CREATE TABLE IF NOT EXISTS `rpg_quests_rewards` (
  `quest_id` mediumint(8) unsigned NOT NULL,
  `item_id` mediumint(8) unsigned NOT NULL,
  `item_type` varchar(100) COLLATE utf8_bin NOT NULL,
  `number` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`quest_id`,`item_id`,`item_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_quests_riddles`
--

CREATE TABLE IF NOT EXISTS `rpg_quests_riddles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `descr` text COLLATE utf8_bin,
  `answer` varchar(200) COLLATE utf8_bin NOT NULL,
  `quest_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quest_id` (`quest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_quest_battles`
--

CREATE TABLE IF NOT EXISTS `rpg_quest_battles` (
  `token` varchar(200) COLLATE utf8_bin NOT NULL,
  `monster_id` mediumint(8) unsigned NOT NULL,
  `monster_hp` int(10) unsigned NOT NULL,
  `monster_fp` int(10) unsigned NOT NULL,
  `bgm` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `forum_id` mediumint(8) unsigned NOT NULL,
  `topic_id` mediumint(8) unsigned NOT NULL,
  `is_over` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`),
  UNIQUE KEY `forum_id` (`forum_id`,`topic_id`),
  UNIQUE KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_quest_battles_players`
--

CREATE TABLE IF NOT EXISTS `rpg_quest_battles_players` (
  `battle_token` varchar(200) COLLATE utf8_bin NOT NULL,
  `player_id` mediumint(8) unsigned NOT NULL,
  `in_battle` tinyint(1) NOT NULL DEFAULT '0',
  `is_dead` tinyint(1) NOT NULL DEFAULT '0',
  `turn` int(10) unsigned NOT NULL DEFAULT '1',
  `player_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `player_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `monster_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_active_skills` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_buffs` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `monster_active_orbs` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`battle_token`,`player_id`),
  UNIQUE KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_ralz`
--

CREATE TABLE IF NOT EXISTS `rpg_ralz` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `desc` varchar(50) COLLATE utf8_bin NOT NULL,
  `img` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_rp_forums`
--

CREATE TABLE IF NOT EXISTS `rpg_rp_forums` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_sets`
--

CREATE TABLE IF NOT EXISTS `rpg_sets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `cloth_id` mediumint(8) unsigned NOT NULL,
  `leggings_id` mediumint(8) unsigned NOT NULL,
  `gloves_id` mediumint(8) unsigned NOT NULL,
  `shoes_id` mediumint(8) unsigned NOT NULL,
  `pv` mediumint(8) NOT NULL,
  `pf` smallint(8) NOT NULL,
  `atk` mediumint(8) NOT NULL,
  `def` mediumint(8) NOT NULL,
  `res` mediumint(8) NOT NULL,
  `vit` mediumint(8) NOT NULL,
  `flux` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cloth_id` (`cloth_id`,`leggings_id`,`gloves_id`,`shoes_id`),
  UNIQUE KEY `name` (`name`),
  KEY `leggings_id` (`leggings_id`),
  KEY `gloves_id` (`gloves_id`),
  KEY `shoes_id` (`shoes_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_shoes`
--

CREATE TABLE IF NOT EXISTS `rpg_shoes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `pv` mediumint(8) NOT NULL DEFAULT '0',
  `pf` mediumint(8) NOT NULL DEFAULT '0',
  `atk` mediumint(8) NOT NULL DEFAULT '0',
  `def` mediumint(8) NOT NULL DEFAULT '0',
  `res` mediumint(8) NOT NULL DEFAULT '0',
  `vit` mediumint(8) NOT NULL DEFAULT '0',
  `flux` mediumint(8) NOT NULL DEFAULT '0',
  `img` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `req_lvl` mediumint(8) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_specials`
--

CREATE TABLE IF NOT EXISTS `rpg_specials` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `descr` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `img` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `effect` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_syringes`
--

CREATE TABLE IF NOT EXISTS `rpg_syringes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `descr` varchar(50) COLLATE utf8_bin NOT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `img` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `usable_outside_battle` tinyint(1) NOT NULL DEFAULT '0',
  `pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `max_pv` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pf` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `max_pf` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `atk` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `def` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `vit` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `flux` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `res` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_upgrades`
--

CREATE TABLE IF NOT EXISTS `rpg_upgrades` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `grade` varchar(2) COLLATE utf8_bin NOT NULL,
  `success_rate` mediumint(8) unsigned NOT NULL DEFAULT '100',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade` (`grade`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_users_players`
--

CREATE TABLE IF NOT EXISTS `rpg_users_players` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `player_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`player_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_warehouse`
--

CREATE TABLE IF NOT EXISTS `rpg_warehouse` (
  `player_id` mediumint(8) unsigned NOT NULL,
  `slot` mediumint(8) unsigned NOT NULL,
  `item_type` varchar(100) COLLATE utf8_bin NOT NULL,
  `item_id` mediumint(8) unsigned NOT NULL,
  `number` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`player_id`,`slot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_weapons`
--

CREATE TABLE IF NOT EXISTS `rpg_weapons` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `grade` varchar(2) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `player_id` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Structure de la table `rpg_xp`
--

CREATE TABLE IF NOT EXISTS `rpg_xp` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `level` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `xp` int(30) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=30 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `rpg_achievements`
--
ALTER TABLE `rpg_achievements`
  ADD CONSTRAINT `rpg_achievements_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `rpg_achievements_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_active_quests`
--
ALTER TABLE `rpg_active_quests`
  ADD CONSTRAINT `rpg_active_quests_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `rpg_quests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_active_quests_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_active_quests_ibfk_3` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_active_quests_ibfk_4` FOREIGN KEY (`topic_id`) REFERENCES `phpbb_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_active_quests_ibfk_5` FOREIGN KEY (`riddle_id`) REFERENCES `rpg_quests_riddles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_active_quests_members`
--
ALTER TABLE `rpg_active_quests_members`
  ADD CONSTRAINT `rpg_active_quests_members_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `rpg_active_quests` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_active_quests_members_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_armor_parts`
--
ALTER TABLE `rpg_armor_parts`
  ADD CONSTRAINT `rpg_armor_parts_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_battle_areas_monsters`
--
ALTER TABLE `rpg_battle_areas_monsters`
  ADD CONSTRAINT `rpg_battle_areas_monsters_ibfk_1` FOREIGN KEY (`area_part_id`) REFERENCES `rpg_battle_areas_parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_battle_areas_monsters_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `rpg_monsters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_battle_areas_parts`
--
ALTER TABLE `rpg_battle_areas_parts`
  ADD CONSTRAINT `rpg_battle_areas_parts_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `rpg_battle_areas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_clans`
--
ALTER TABLE `rpg_clans`
  ADD CONSTRAINT `rpg_clans_ibfk_1` FOREIGN KEY (`leader_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_clans_join_requests`
--
ALTER TABLE `rpg_clans_join_requests`
  ADD CONSTRAINT `rpg_clans_join_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_clans_join_requests_ibfk_2` FOREIGN KEY (`clan_id`) REFERENCES `rpg_clans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_clans_members`
--
ALTER TABLE `rpg_clans_members`
  ADD CONSTRAINT `rpg_clans_members_ibfk_1` FOREIGN KEY (`clan_id`) REFERENCES `rpg_clans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_clans_members_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_clans_messages`
--
ALTER TABLE `rpg_clans_messages`
  ADD CONSTRAINT `rpg_clans_messages_ibfk_1` FOREIGN KEY (`clan_id`) REFERENCES `rpg_clans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_clans_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_event_battles_items`
--
ALTER TABLE `rpg_event_battles_items`
  ADD CONSTRAINT `rpg_event_battles_items_ibfk_1` FOREIGN KEY (`battle_token`) REFERENCES `rpg_event_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_event_battles_players`
--
ALTER TABLE `rpg_event_battles_players`
  ADD CONSTRAINT `battle_token` FOREIGN KEY (`battle_token`) REFERENCES `rpg_event_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `player_id` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_event_battles_registered_players`
--
ALTER TABLE `rpg_event_battles_registered_players`
  ADD CONSTRAINT `rpg_event_battles_registered_players_ibfk_1` FOREIGN KEY (`token`) REFERENCES `rpg_event_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_event_battles_registered_players_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_inventories`
--
ALTER TABLE `rpg_inventories`
  ADD CONSTRAINT `rpg_inventories_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_karma_topics`
--
ALTER TABLE `rpg_karma_topics`
  ADD CONSTRAINT `rpg_karma_topics_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_karma_topics_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `phpbb_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_karma_topics_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_monsters_drops`
--
ALTER TABLE `rpg_monsters_drops`
  ADD CONSTRAINT `rpg_monsters_drops_ibfk_1` FOREIGN KEY (`monster_id`) REFERENCES `rpg_monsters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_monsters_drops_ibfk_2` FOREIGN KEY (`area_part_id`) REFERENCES `rpg_battle_areas_parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_monsters_skills`
--
ALTER TABLE `rpg_monsters_skills`
  ADD CONSTRAINT `rpg_monsters_skills_ibfk_1` FOREIGN KEY (`monster_id`) REFERENCES `rpg_monsters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_monster_books`
--
ALTER TABLE `rpg_monster_books`
  ADD CONSTRAINT `rpg_monster_books_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_monster_books_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `rpg_monsters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_players`
--
ALTER TABLE `rpg_players`
  ADD CONSTRAINT `rpg_players_ibfk_5` FOREIGN KEY (`clan_id`) REFERENCES `rpg_clans` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `rpg_players_achievements`
--
ALTER TABLE `rpg_players_achievements`
  ADD CONSTRAINT `rpg_players_achievements_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_players_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `rpg_achievements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_players_stats`
--
ALTER TABLE `rpg_players_stats`
  ADD CONSTRAINT `rpg_players_stats_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_pve_battles`
--
ALTER TABLE `rpg_pve_battles`
  ADD CONSTRAINT `rpg_pve_battles_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pve_battles_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `rpg_monsters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pve_battles_ibfk_3` FOREIGN KEY (`area_part_id`) REFERENCES `rpg_battle_areas_parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_pvp_battles`
--
ALTER TABLE `rpg_pvp_battles`
  ADD CONSTRAINT `rpg_pvp_battles_ibfk_1` FOREIGN KEY (`player1_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pvp_battles_ibfk_2` FOREIGN KEY (`player2_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_pvp_battles_actions`
--
ALTER TABLE `rpg_pvp_battles_actions`
  ADD CONSTRAINT `rpg_pvp_battles_actions_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pvp_battles_actions_ibfk_2` FOREIGN KEY (`battle_token`) REFERENCES `rpg_pvp_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_pvp_battles_requests`
--
ALTER TABLE `rpg_pvp_battles_requests`
  ADD CONSTRAINT `rpg_pvp_battles_requests_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pvp_battles_requests_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pvp_battles_requests_ibfk_3` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pvp_battles_requests_ibfk_4` FOREIGN KEY (`topic_id`) REFERENCES `phpbb_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_pvp_battles_requests_ibfk_5` FOREIGN KEY (`battle_token`) REFERENCES `rpg_pvp_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_pvp_battles_turn_results`
--
ALTER TABLE `rpg_pvp_battles_turn_results`
  ADD CONSTRAINT `rpg_pvp_battles_turn_results_ibfk_1` FOREIGN KEY (`token`) REFERENCES `rpg_pvp_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_pvp_forums`
--
ALTER TABLE `rpg_pvp_forums`
  ADD CONSTRAINT `rpg_pvp_forums_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_quests`
--
ALTER TABLE `rpg_quests`
  ADD CONSTRAINT `rpg_quests_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_quests_ibfk_2` FOREIGN KEY (`monster_id`) REFERENCES `rpg_monsters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_quests_rewards`
--
ALTER TABLE `rpg_quests_rewards`
  ADD CONSTRAINT `rpg_quests_rewards_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `rpg_quests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_quests_riddles`
--
ALTER TABLE `rpg_quests_riddles`
  ADD CONSTRAINT `rpg_quests_riddles_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `rpg_quests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_quest_battles`
--
ALTER TABLE `rpg_quest_battles`
  ADD CONSTRAINT `rpg_quest_battles_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_quest_battles_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `phpbb_topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_quest_battles_players`
--
ALTER TABLE `rpg_quest_battles_players`
  ADD CONSTRAINT `rpg_quest_battles_players_ibfk_1` FOREIGN KEY (`battle_token`) REFERENCES `rpg_quest_battles` (`token`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_quest_battles_players_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_rp_forums`
--
ALTER TABLE `rpg_rp_forums`
  ADD CONSTRAINT `rpg_rp_forums_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `phpbb_forums` (`forum_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_sets`
--
ALTER TABLE `rpg_sets`
  ADD CONSTRAINT `rpg_sets_ibfk_1` FOREIGN KEY (`cloth_id`) REFERENCES `rpg_clothes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_sets_ibfk_2` FOREIGN KEY (`leggings_id`) REFERENCES `rpg_leggings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_sets_ibfk_3` FOREIGN KEY (`gloves_id`) REFERENCES `rpg_gloves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_sets_ibfk_4` FOREIGN KEY (`shoes_id`) REFERENCES `rpg_shoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_users_players`
--
ALTER TABLE `rpg_users_players`
  ADD CONSTRAINT `rpg_users_players_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `phpbb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rpg_users_players_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_warehouse`
--
ALTER TABLE `rpg_warehouse`
  ADD CONSTRAINT `rpg_warehouse_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `rpg_weapons`
--
ALTER TABLE `rpg_weapons`
  ADD CONSTRAINT `rpg_weapons_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `rpg_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
