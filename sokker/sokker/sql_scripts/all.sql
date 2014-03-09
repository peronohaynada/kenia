CREATE TABLE `test`.`usuario` (
	`usuario_id` int(11) NOT NULL AUTO_INCREMENT,
	`nombre_usuario` VARCHAR(64) NOT NULL,
	`contrasena_usuario` VARCHAR(256) NOT NULL,
	`confirmacion_credenciales_sokker` tinyint(1) DEFAULT 0,
	PRIMARY KEY  (`usuario_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `test`.`x_usuario_sokker_team` (
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`usuario_id` int(11)NOT NULL,
	`sokker_team_id` VARCHAR(256) NOT NULL,
	`usuario_sokker` VARCHAR(256) NOT NULL,
	`contrasena_sokker` VARCHAR(256) NULL,
	PRIMARY KEY (`id`),
	KEY `usuario` (`usuario_id`),
	CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`)
	  REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `test`.`juniors` (
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`junior_id` VARCHAR(178) NOT NULL,
	`sokker_team_id` INTEGER NOT NULL,
	`nombre` VARCHAR(256) NOT NULL,
	`apellido` VARCHAR(256) NOT NULL,
	`edad` VARCHAR(256) NOT NULL,
	`altura` VARCHAR(256) NOT NULL,
	`peso` VARCHAR(256) NOT NULL,
	`imc` VARCHAR(256) NOT NULL,
	`formacion` tinyint(1) NOT NULL DEFAULT 1,
	`semanas` SMALLINT(10) NOT NULL,
	`sigue_en_escuela` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`id`),
	KEY `sokker_team` (`sokker_team_id`),
	CONSTRAINT `fk_sokker_team` FOREIGN KEY (`sokker_team_id`)
	  REFERENCES `x_usuario_sokker_team` (`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `test`.`habilidad_junior` (
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`junior_id` INTEGER NOT NULL,
	`habilidad` INTEGER NOT NULL,
	`semanas` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	KEY `junior` (`junior_id`),
	CONSTRAINT `fk_juniors` FOREIGN KEY (`junior_id`)
	  REFERENCES `juniors` (`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `test`.`countries` (
	`country_id` SMALLINT(100) NOT NULL,
	`nombre` VARCHAR(256) NOT NULL,
	`currency_name` VARCHAR(256) NOT NULL,
	`currency_rate` VARCHAR(256) NOT NULL,
	PRIMARY KEY (`country_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


 CREATE TABLE `test`.`team` (
 	`sokker_team_id` INTEGER NOT NULL,
	`id` INTEGER NOT NULL AUTO_INCREMENT,
 	`team_id` VARCHAR(512) NOT NULL,
 	`nombre` VARCHAR(512) NOT NULL,
 	`country_id` SMALLINT(100) NOT NULL,
 	`region_id` SMALLINT(100) NOT NULL,
 	`date_created` DATE NOT NULL,
 	`rank` DOUBLE(255, 2) NOT NULL,
 	`national` SMALLINT(1) NOT NULL,
 	`arena_name` VARCHAR(512) NOT NULL,
 	`fanclub_mood` SMALLINT(1) NOT NULL,
 	`juniors_max` SMALLINT(2) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `sokker_team` (`sokker_team_id`),
	CONSTRAINT `fk_sokker_team_team` FOREIGN KEY (`sokker_team_id`)
	  REFERENCES `x_usuario_sokker_team` (`id`) ON DELETE CASCADE,
	KEY `country` (`country_id`),
	CONSTRAINT `fk_country` FOREIGN KEY (`country_id`)
	  REFERENCES `countries` (`country_id`) ON DELETE CASCADE
 ) ENGINE=INNODB DEFAULT CHARSET=utf8;
