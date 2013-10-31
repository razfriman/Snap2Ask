SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `snap2ask` DEFAULT CHARACTER SET latin1 ;
USE `snap2ask` ;

-- -----------------------------------------------------
-- Table `snap2ask`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`subcategories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`subcategories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `category_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_subcategories_1_idx` (`category_id` ASC),
  CONSTRAINT `fk_subcategories_1`
    FOREIGN KEY (`category_id`)
    REFERENCES `snap2ask`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `oauth_id` VARCHAR(100) NULL,
  `password` VARCHAR(100) NOT NULL,
  `salt` VARCHAR(100) NOT NULL,
  `balance` INT(11) NOT NULL DEFAULT '10',
  `is_tutor` TINYINT(4) NOT NULL DEFAULT '0',
  `is_admin` TINYINT(4) NOT NULL DEFAULT '0',
  `date_created` DATETIME NOT NULL,
  `authentication_mode` ENUM('custom','facebook','google') NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `rating` INT(11) NULL,
  `password_reset_token` VARCHAR(50) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC, `authentication_mode` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 55
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`questions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `description` VARCHAR(512) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `subcategory_id` INT(11) NOT NULL,
  `status` INT(11) NOT NULL DEFAULT '0',
  `times_answered` INT(11) NOT NULL DEFAULT '0',
  `image_url` VARCHAR(255) NOT NULL,
  `image_thumbnail_url` VARCHAR(255) NOT NULL,
  `date_created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_questions_categories_idx` (`category_id` ASC),
  INDEX `fk_questions_answers1_idx` (`status` ASC),
  INDEX `fk_questions_users1_idx` (`student_id` ASC),
  INDEX `fk_questions_1_idx` (`subcategory_id` ASC),
  CONSTRAINT `fk_questions_1`
    FOREIGN KEY (`subcategory_id`)
    REFERENCES `snap2ask`.`subcategories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_categories`
    FOREIGN KEY (`category_id`)
    REFERENCES `snap2ask`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_users1`
    FOREIGN KEY (`student_id`)
    REFERENCES `snap2ask`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 31
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`answers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) NOT NULL,
  `tutor_id` INT(11) NOT NULL,
  `text` VARCHAR(512) NOT NULL,
  `rating` SMALLINT(6) NULL DEFAULT NULL,
  `status` VARCHAR(45) NOT NULL DEFAULT 'pending',
  `date_created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_answers_1_idx` (`question_id` ASC),
  INDEX `fk_answers_2_idx` (`tutor_id` ASC),
  CONSTRAINT `fk_answers_1`
    FOREIGN KEY (`question_id`)
    REFERENCES `snap2ask`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_answers_2`
    FOREIGN KEY (`tutor_id`)
    REFERENCES `snap2ask`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`verified_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`verified_categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `is_preferred` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index2` (`user_id` ASC, `category_id` ASC),
  INDEX `fk_verified_categories_2_idx` (`category_id` ASC),
  CONSTRAINT `fk_verified_categories_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `snap2ask`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_verified_categories_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `snap2ask`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `snap2ask`.`verification_questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`verification_questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  `choice_1` VARCHAR(255) NOT NULL,
  `choice_2` VARCHAR(255) NOT NULL,
  `choice_3` VARCHAR(255) NOT NULL,
  `choice_4` VARCHAR(255) NOT NULL,
  `correct_choice` SMALLINT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_verification_questions_1_idx` (`category_id` ASC),
  CONSTRAINT `fk_verification_questions_1`
    FOREIGN KEY (`category_id`)
    REFERENCES `snap2ask`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
