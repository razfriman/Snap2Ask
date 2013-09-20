SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `snap2ask` DEFAULT CHARACTER SET latin1 ;
USE `snap2ask` ;

-- -----------------------------------------------------
-- Table `snap2ask`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`answers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) NOT NULL,
  `tutor_id` INT(11) NOT NULL,
  `text` VARCHAR(512) NOT NULL,
  `rating` SMALLINT(6) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`authentication_modes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`authentication_modes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `data` BLOB NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `salt` VARCHAR(100) NOT NULL,
  `balance` INT(11) NOT NULL DEFAULT '10',
  `is_tutor` TINYINT(4) NOT NULL DEFAULT '0',
  `is_admin` TINYINT(4) NOT NULL DEFAULT '0',
  `authentication_mode_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_users_authentication_modes1_idx` (`authentication_mode_id` ASC),
  CONSTRAINT `fk_users_authentication_modes1`
    FOREIGN KEY (`authentication_mode_id`)
    REFERENCES `snap2ask`.`authentication_modes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `snap2ask`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snap2ask`.`questions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `desc` VARCHAR(255) NULL DEFAULT NULL,
  `categories_id` INT(11) NOT NULL,
  `answers_id` INT(11) NULL,
  `images_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_questions_categories_idx` (`categories_id` ASC),
  INDEX `fk_questions_answers1_idx` (`answers_id` ASC),
  INDEX `fk_questions_images1_idx` (`images_id` ASC),
  INDEX `fk_questions_users1_idx` (`student_id` ASC),
  CONSTRAINT `fk_questions_categories`
    FOREIGN KEY (`categories_id`)
    REFERENCES `snap2ask`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_answers1`
    FOREIGN KEY (`answers_id`)
    REFERENCES `snap2ask`.`answers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_images1`
    FOREIGN KEY (`images_id`)
    REFERENCES `snap2ask`.`images` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_users1`
    FOREIGN KEY (`student_id`)
    REFERENCES `snap2ask`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
