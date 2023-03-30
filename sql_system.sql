CREATE SCHEMA `sip_lanteca` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(75) NOT NULL,
  `user_login` varchar(30) NOT NULL,
  `user_nome` varchar(30) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_pass` varchar(80) NOT NULL,
  `user_passres` int(1) NOT NULL,
  `user_sts` int(1) NOT NULL,
  `user_lastlogin` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email_idx` (`user_email`),
  KEY `login_idx` (`user_nome`,`user_email`,`user_pass`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `sip_lanteca`.`users` (`user_fullname`, `user_login`, `user_nome`, `user_email`, `user_pass`, `user_passres`, `user_sts`) VALUES ('ADMIN', 'ADMIN', 'ADMIN', 'douglaassgenesis@gmail.com', '$2y$10$Vl7JDZ..o3mLI/zAinbxVuOYQadJCfV5qhgeDee6OuTmAfCbHQfr.', '0', '1');
