-- ==============================================================================
-- Creation des tables pour netweb1.1
-- ==============================================================================

DROP TABLE IF EXISTS `ip_host`;

CREATE TABLE `ip_host` (
	`iph_client` INT(11) NOT NULL AUTO_INCREMENT,
	`iph_name` VARCHAR(20) NOT NULL DEFAULT '',
	`iph_domain` VARCHAR(50) NOT NULL DEFAULT '',
	`iph_ip` VARCHAR(15) NOT NULL DEFAULT '',
	`iph_community` VARCHAR(40) NOT NULL DEFAULT '',
	`iph_version` INT(11) NOT NULL DEFAULT 1,
	`iph_description` TEXT,
	PRIMARY KEY (`iph_client`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
