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
  `user_lastlogin` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email_idx` (`user_email`),
  KEY `login_idx` (`user_nome`,`user_email`,`user_pass`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `sip_lanteca`.`users` (`user_fullname`, `user_login`, `user_nome`, `user_email`, `user_pass`, `user_passres`, `user_sts`) VALUES ('ADMIN', 'ADMIN', 'ADMIN', 'douglaassgenesis@gmail.com', '$2y$10$Vl7JDZ..o3mLI/zAinbxVuOYQadJCfV5qhgeDee6OuTmAfCbHQfr.', '0', '1');


CREATE TABLE `sip_lanteca`.`callback` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cpf_callback` VARCHAR(14) NOT NULL,
  `numero_callback` VARCHAR(14) NOT NULL,
  `data_callback` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_retornada` TIMESTAMP NULL DEFAULT NULL,
  `operador_retornou` VARCHAR(6) NULL DEFAULT NULL,
  `id_status_callback` INT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`));

CREATE TABLE `sip_lanteca`.`status_callback` (
  `id_status` INT NOT NULL AUTO_INCREMENT,
  `nome_status` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id_status`));


  ALTER TABLE `sip_lanteca`.`callback` 
ADD INDEX `callbacl_status_fk_idx` (`status_callback` ASC);
;
ALTER TABLE `sip_lanteca`.`callback` 
ADD CONSTRAINT `callbacl_status_fk`
  FOREIGN KEY (`id_status_callback`)
  REFERENCES `sip_lanteca`.`status_callback` (`id_status`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;
