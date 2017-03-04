-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema just_meet
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema just_meet
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `just_meet` DEFAULT CHARACTER SET utf8 ;
USE `just_meet` ;

-- -----------------------------------------------------
-- Table `just_meet`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `just_meet`.`user` ;

CREATE TABLE IF NOT EXISTS `just_meet`.`user` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(30) NOT NULL,
    `second_name` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NULL,
    PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `just_meet`.`meeting`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `just_meet`.`meeting`;

CREATE TABLE IF NOT EXISTS `just_meet`.`meeting` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NULL,
    PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `just_meet`.`attendee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `just_meet`.`attendee`;

CREATE TABLE IF NOT EXISTS `just_meet`.`attendee` (
    `user_id` INT UNSIGNED NOT NULL,
    `meeting_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY(`user_id`, `meeting_id`),
    INDEX `fk_attendee_to_user_idx` (`user_id` ASC),
    INDEX `fk_attendee_to_meeting_idx` (`meeting_id` ASC),
    CONSTRAINT `fk_attendee_to_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `just_meet`.`user` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_attendee_to_meeting`
        FOREIGN KEY (`meeting_id`)
        REFERENCES `just_meet`.`meeting` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `just_meet`.`action`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `just_meet`.`action`;

CREATE TABLE IF NOT EXISTS `just_meet`.`action` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `meeting_id` INT UNSIGNED NOT NULL,
    `topic` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    PRIMARY KEY(`id`),
    INDEX `fk_action_to_meeting_idx` (`meeting_id` ASC),
    CONSTRAINT `fk_action_to_meeting`
        FOREIGN KEY (`meeting_id`)
        REFERENCES `just_meet`.`meeting` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `just_meet`.`action_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `just_meet`.`action_user`;

CREATE TABLE IF NOT EXISTS `just_meet`.`action_user` (
    `user_id` INT UNSIGNED NOT NULL,
    `action_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `action_id`),
    INDEX `fk_action_user_to_user_idx` (`user_id` ASC),
    INDEX `fk_action_user_to_action_idx` (`action_id` ASC),
    CONSTRAINT `fk_action_user_to_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `just_meet`.`user` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_action_user_action`
        FOREIGN KEY (`action_id`)
        REFERENCES `just_meet`.`action` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `just_meet`.`agenda`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `just_meet`.`agenda`;

CREATE TABLE IF NOT EXISTS `just_meet`.`agenda` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `meeting_id` INT UNSIGNED NOT NULL,
    `topic` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_agenda_to_meeting_idx` (`meeting_id` ASC),
    CONSTRAINT `fk_agenda_to_meeting`
        FOREIGN KEY (`meeting_id`)
        REFERENCES `just_meet`.`meeting` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
