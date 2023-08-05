-- Atualização 04/08/2023 
    -- Adicionando coluna de ouvir ligação

INSERT INTO `users_permissions` (`id`, `permission_label`, `path_permission`, `category`, `type`) VALUES ('33', 'Ouvir gravação de ligação', '/api/calls_report/get_recorded_audio/[0-9]+', 'Call Reports', 'action');
