CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `password` varchar(70) NOT NULL COMMENT 'hasło',
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'Full name (first and last), along with scientific title or degree',
  `email` varchar(175) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'E-mail address',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Access level',
  `note` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'Internal note',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `users` VALUES(NULL, '', 'LDAP User', 'ldap.user@company.com', 4, '');
INSERT INTO `users` VALUES(NULL, 'fe01ce2a7fbac8fafaed7c982a04e229', 'Local Demo User', 'demo.user@company.com', 1, '');
INSERT INTO `users` VALUES(NULL, '21232f297a57a5a743894a0e4a801fc3', 'Local Admin User', 'admin.user@company.com', 4, '');