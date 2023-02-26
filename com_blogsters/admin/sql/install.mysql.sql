--
-- Table structure for table `#__blogsters`
--

CREATE TABLE IF NOT EXISTS `#__blogsters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `description` longtext DEFAULT NULL,
  `featured_image` varchar(150) DEFAULT NULL,
  `featured_image_alt_text` varchar(250) DEFAULT NULL,
  `primary_is_featured` int(2) DEFAULT 0 COMMENT '0=default, 1=primary featured',
  `is_featured` int(2) DEFAULT 0 COMMENT '0=default, 1=featured',
  `tags_id` varchar(250) DEFAULT NULL,
  `read_time` double DEFAULT 0 COMMENT 'unit=minutes',
  `created` datetime DEFAULT NULL,
  `state` int(1) DEFAULT 0 COMMENT '0=in active, 1= active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;

--
-- Table structure for table `#__blogsters_categories`
--

CREATE TABLE IF NOT EXISTS `#__blogsters_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `state` int(2) DEFAULT 0 COMMENT '0=in active, 1= active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;

--
-- Table structure for table `#__blogsters_votings`
--

CREATE TABLE IF NOT EXISTS `#__blogsters_votings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `up_vote` int(2) DEFAULT 0 COMMENT '0=default, 1= voted',
  `down_vote` int(2) DEFAULT 0 COMMENT '0=default, 1= voted',
  `ip` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `state` int(2) DEFAULT 0 COMMENT '0=in active, 1=active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;

--
-- Table structure for table `#__blogsters_authors`
--

CREATE TABLE IF NOT EXISTS `#__blogsters_authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `author_bio` longtext DEFAULT NULL,
  `designation` varchar(250) DEFAULT NULL,
  `profile_image` varchar(250) DEFAULT NULL,
  `state` int(2) DEFAULT 0 COMMENT '0=in active, 1= active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;

--
-- Table structure for table `#__blogsters_tags`
--

CREATE TABLE IF NOT EXISTS `#__blogsters_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `state` int(2) DEFAULT 0 COMMENT '0=in active, 1= active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;