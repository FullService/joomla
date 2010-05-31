-- -----------------------------------------------------
-- Table `joomla`.`#__projects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `alias` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL ,
  `start_at` DATE NOT NULL ,
  `catid` INT(11) UNSIGNED NOT NULL DEFAULT 0 ,
  `finish_at` DATE NOT NULL ,
  `ordering` INT NOT NULL ,
  `hits` INT UNSIGNED NOT NULL ,
  `created` DATETIME NOT NULL ,
  `created_by` INT UNSIGNED NOT NULL ,
  `created_by_alias` VARCHAR(255) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `modified_by` INT UNSIGNED NOT NULL ,
  `state` TINYINT(1) NOT NULL DEFAULT 1 ,
  `language` CHAR(7) NOT NULL ,
  `featured` TINYINT(3) UNSIGNED NOT NULL ,
  `xreference` VARCHAR(50) NOT NULL ,
  `params` TEXT NOT NULL ,
  `checked_out` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `checked_out_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_catid` (`catid` ASC) ,
  INDEX `idx_xreference` (`xreference` ASC) ,
  INDEX `idx_language` (`language` ASC) ,
  INDEX `idx_checked_out` (`checked_out` ASC) ,
  INDEX `idx_alias` (`alias` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'project table\n';


-- -----------------------------------------------------
-- Table `joomla`.`#__project_activities`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__project_activities` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(255) NOT NULL DEFAULT 0 ,
  `description` TEXT NOT NULL ,
  `link` TEXT NULL ,
  `created` DATETIME NOT NULL ,
  `created_by` INT UNSIGNED NOT NULL ,
  `created_by_alias` VARCHAR(255) NOT NULL ,
  `language` CHAR(7) NOT NULL ,
  `featured` TINYINT(3) UNSIGNED NOT NULL ,
  `xreference` VARCHAR(50) NOT NULL ,
  `params` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_xreference` (`xreference` ASC) ,
  INDEX `idx_language` (`language` ASC) ,
  INDEX `idx_type` (`type` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Activities message board is the twitter of the project';


-- -----------------------------------------------------
-- Table `joomla`.`#__project_members`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__project_members` (
  `project_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `group_id` INT NULL ,
  `asset_id` INT NULL ,
  PRIMARY KEY (`project_id`, `user_id`) ,
  INDEX `idx_group_id` (`group_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'A project can have many menber with diferent roles on diferent projects';


-- -----------------------------------------------------
-- Table `joomla`.`#__project_contents`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__project_contents` (
  `project_id` INT NOT NULL ,
  `content_id` INT NOT NULL ,
  PRIMARY KEY (`project_id`, `content_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'A project can have related content itens ';


-- -----------------------------------------------------
-- Table `joomla`.`#__project_tasks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__project_tasks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type_id` INT UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `alias` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL ,
  `estimate` INT UNSIGNED NOT NULL ,
  `start_at` DATE NOT NULL ,
  `finish_at` DATE NOT NULL ,
  `finished` DATETIME NULL ,
  `finished_by` INT NULL ,
  `is_ticket` TINYINT(1) NOT NULL DEFAULT 0 ,
  `ordering` INT NOT NULL ,
  `hits` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `created` DATETIME NOT NULL ,
  `created_by` INT UNSIGNED NOT NULL ,
  `created_by_alias` VARCHAR(255) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `modified_by` INT UNSIGNED NOT NULL ,
  `state` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Non aproved tasks are tikets' ,
  `language` CHAR(7) NOT NULL ,
  `featured` TINYINT(3) UNSIGNED NOT NULL ,
  `xreference` VARCHAR(50) NOT NULL ,
  `params` TEXT NOT NULL ,
  `checked_out` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `checked_out_time` DATETIME NOT NULL ,
  `parent_id` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `rgt` INT NOT NULL DEFAULT 0 ,
  `lft` INT NOT NULL DEFAULT 0 ,
  `level` INT UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_xreference` (`xreference` ASC) ,
  INDEX `idx_language` (`language` ASC) ,
  INDEX `idx_checked_out` (`checked_out` ASC) ,
  INDEX `idx_alias` (`alias` ASC) ,
  INDEX `idx_type_id` (`type_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'a project can have many tasks, a task can belong to another tasks (multlevel)\nthe finished marks if the task is over and when';


-- -----------------------------------------------------
-- Table `joomla`.`#__project_task_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__project_task_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `alias` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL ,
  `ordering` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `created_by` INT UNSIGNED NOT NULL ,
  `created_by_alias` VARCHAR(255) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `modified_by` INT UNSIGNED NOT NULL ,
  `state` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Non aproved tasks are tikets' ,
  `language` CHAR(7) NOT NULL ,
  `featured` TINYINT(3) UNSIGNED NOT NULL ,
  `xreference` VARCHAR(50) NOT NULL ,
  `params` TEXT NOT NULL ,
  `checked_out` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `checked_out_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_xreference` (`xreference` ASC) ,
  INDEX `idx_language` (`language` ASC) ,
  INDEX `idx_checked_out` (`checked_out` ASC) ,
  INDEX `idx_alias` (`alias` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'a project can have many tasks, a task can belong to another tasks (multlevel)\nthe finished marks if the task is over and when';

-- -----------------------------------------------------
-- Table `joomla`.`#__project_task_members`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__project_task_members` (
  `task_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`task_id`, `user_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'A project can have many menber with diferent roles on diferent projects';

