CREATE DATABASE  IF NOT EXISTS `snap2ask`;
USE `snap2ask`;


--
-- Table structure for table `categories`
--
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`),
  FULLTEXT INDEX `index2` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Table structure for table `subcategories`
--
DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`, `category_id`),
    FULLTEXT INDEX `index2` (`name`),
  CONSTRAINT `fk_subcategories_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `oauth_id` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '10',
  `is_tutor` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `authentication_mode` enum('custom','facebook','google') NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `average_rating` int(11),
  `password_reset_token` VARCHAR(50) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`,`authentication_mode`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- 
-- Table structure for table `verified_categories`
-- 
DROP TABLE IF EXISTS `verified_categories`;
CREATE TABLE IF NOT EXISTS `verified_categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `is_preferred` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index2` (`user_id` ASC, `category_id` ASC),
  INDEX `fk_verified_categories_2_idx` (`category_id` ASC),
  CONSTRAINT `fk_verified_categories_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_verified_categories_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = MyISAM;

--
-- Table structure for table `validationQuestions`
--
DROP TABLE IF EXISTS `validationQuestions`;
CREATE TABLE `validationQuestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(512) NOT NULL,
  `optionA` varchar(150) NOT NULL,
  `optionB` varchar(150) NOT NULL,
  `optionC` varchar(150) NOT NULL,
  `rightAnswer` varchar(2) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index` (`question`, `optionA`, `optionB`, `optionC`, `rightAnswer`, `category_id`),
  CONSTRAINT `fk_validationQuestions_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET = latin1;



--
-- Table structure for table `questions`
--
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_hidden` tinyint(4) NOT NULL DEFAULT '0',
  `times_answered` int(11) NOT NULL DEFAULT '0',
  `image_url` varchar(255) NOT NULL,
  `image_thumbnail_url` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT INDEX `index2` (`description`),
  KEY `fk_questions_categories_idx` (`category_id`),
  KEY `fk_questions_answers1_idx` (`status`),
  KEY `fk_questions_users1_idx` (`student_id`),
  KEY `fk_questions_1_idx` (`subcategory_id`),
  UNIQUE KEY `question_UNIQUE` (`description`(512),`image_url`),
  CONSTRAINT `fk_questions_subcategories` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_users` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


--
-- Table structure for table `answers`
--
DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `tutor_id` int(11) NULL,
  `text` text NOT NULL,
  `rating` smallint(6) DEFAULT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'pending',
  `pay` int(11) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_answers_questions` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_answers_users` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


