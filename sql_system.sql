CREATE TABLE `sip_lanteca`.`group_permission` (
  `id_group_permission` INT NOT NULL AUTO_INCREMENT,
  `group_permission_description` VARCHAR(45) NOT NULL,
  `group_permission_created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `group_permission_modified` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `group_permission_status` TINYINT(1) NOT NULL DEFAULT 1,
  `id_empresa` INT NOT NULL,
  PRIMARY KEY (`id_group_permission`));

CREATE TABLE `sip_lanteca`.`group_permission_x_permission` (
  `id_group_permission_x_permission` INT NOT NULL AUTO_INCREMENT,
  `id_group_permission` INT NOT NULL,
  `id_permission` INT NOT NULL,
  PRIMARY KEY (`id_group_permission_x_permission`));


CREATE TABLE `sip_lanteca`.`group_permission_x_user` (
  `id_group_permission_x_user` INT NOT NULL AUTO_INCREMENT,
  `id_group_permission` INT NOT NULL,
  `id_user` INT NOT NULL,
  PRIMARY KEY (`id_group_permission_x_user`));

  ALTER TABLE `sip_lanteca`.`group_permission_x_user` 
ADD UNIQUE INDEX `id_user_UNIQUE` (`id_user` ASC) ;
;


INSERT INTO `sip_lanteca`.`users_permissions` (`permission_label`, `path_permission`, `category`, `type`) VALUES ('Grupo de permissões', '/system/group_permission', 'Grupo Permissões', 'view');
INSERT INTO `sip_lanteca`.`users_permissions` (`same_as`, `permission_label`, `path_permission`, `category`, `type`) VALUES ('35', 'Lista de permissões', '/api/system/group_permission/get_list', 'Grupo Permissões', 'same');
INSERT INTO `sip_lanteca`.`users_permissions` (`permission_label`, `category`, `type`) VALUES ('Botão novo grupo', 'Grupo Permissões', 'others');
INSERT INTO `sip_lanteca`.`users_permissions` (`permission_label`, `path_permission`, `category`, `type`) VALUES ('Novo grupo', '/api/system/group_permission/new_group_permission', 'Grupo Permissões', 'action');
INSERT INTO `sip_lanteca`.`users_permissions` (`permission_label`, `path_permission`, `category`, `type`) VALUES ('Botão Atualizar Grupo', '', 'Grupo Permissões', 'others');
INSERT INTO `sip_lanteca`.`users_permissions` (`permission_label`, `path_permission`, `category`, `type`) VALUES ('Atualizar Grupo', '/api/system/group_permission/update_group_permission/[0-9]+', 'Grupo Permissões', 'action');
INSERT INTO `sip_lanteca`.`users_permissions` (`same_as`, `permission_label`, `path_permission`, `category`, `type`) VALUES ('39', 'Grupo Para Form', '/api/system/group_permission/get_group_form/[0-9]+', 'Grupo Permissões', 'same');
