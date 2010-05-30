SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;

-- -----------------------------------------------------
-- Table `mydb`.`#__projects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`#__projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `catid` INT(11) UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `alias` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL ,
  `start_at` DATE NOT NULL ,
  `finish_at` DATE NOT NULL ,
  `created` DATETIME NOT NULL ,
  `created_by` INT UNSIGNED NOT NULL ,
  `created_by_alias` VARCHAR(255) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `modified_by` INT UNSIGNED NOT NULL ,
  `ordering` INT NOT NULL ,
  `approved` TINYINT(1) NOT NULL DEFAULT 1 ,
  `published` INT NOT NULL DEFAULT 0 ,
  `language` CHAR(7) NOT NULL ,
  `featured` TINYINT(3) UNSIGNED NOT NULL ,
  `xreference` VARCHAR(50) NOT NULL ,
  `params` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
