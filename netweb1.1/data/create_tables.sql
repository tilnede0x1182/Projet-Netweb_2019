-- ==============================================================================
-- Creation des tables pour netweb1.1
-- ==============================================================================

DROP TABLE IF EXISTS `ip_address`;
DROP TABLE IF EXISTS `ip_host`;

-- ------------------------------------------------------------------------------
-- Table ip_host : equipements reseau
-- ------------------------------------------------------------------------------

CREATE TABLE `ip_host` (
	`iph_client` INT(11) NOT NULL AUTO_INCREMENT,
	`iph_name` VARCHAR(20) NOT NULL DEFAULT '',
	`iph_domain` VARCHAR(50) NOT NULL DEFAULT '',
	`iph_ether` VARCHAR(17) NOT NULL DEFAULT '',
	`iph_gpsnum` VARCHAR(20) NOT NULL DEFAULT '',
	`iph_type` VARCHAR(30) NOT NULL DEFAULT '',
	`iph_dnsstate` VARCHAR(10) NOT NULL DEFAULT '',
	`iph_ug` VARCHAR(20) NOT NULL DEFAULT '',
	`iph_affect` VARCHAR(50) NOT NULL DEFAULT '',
	`iph_desc` VARCHAR(100) NOT NULL DEFAULT '',
	`iph_location` VARCHAR(50) NOT NULL DEFAULT '',
	`iph_switchport` VARCHAR(30) NOT NULL DEFAULT '',
	`iph_lastupdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`iph_client`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ------------------------------------------------------------------------------
-- Table ip_address : adresses IP
-- ------------------------------------------------------------------------------

CREATE TABLE `ip_address` (
	`ipa_addr` VARCHAR(15) NOT NULL DEFAULT '',
	`ipa_client` INT(11) DEFAULT NULL,
	`ipa_vlanid` SMALLINT(6) NOT NULL DEFAULT '0',
	`ipa_status` ENUM('free','allocated','suspended') NOT NULL DEFAULT 'free',
	`ipa_dhcp` ENUM('true','false') NOT NULL DEFAULT 'true',
	`ipa_lastup` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	UNIQUE KEY `ipa_addr` (`ipa_addr`),
	UNIQUE KEY `ipa_client` (`ipa_client`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
