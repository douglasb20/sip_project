<?php

namespace App\Model;

class UsersPermissionsXUsersDAO extends \Core\Defaults\DefaultModel{
    public $tabela = 'users_permissions_x_users';

    /**
    * Função para verificar permissões
    * @author Douglas A. Silva
    * @param int $id_user
    * @param string $uri
    * @return array
    */
    public function validatePermission($id_user, $uri){
        try{
            $query = "  SELECT * FROM {$this->tabela} as upu
                        INNER JOIN users_permissions up ON upu.id_permission = up.id
                        WHERE upu.id_user = {$id_user} 
                        AND '$uri' REGEXP CONCAT('^', up.path_permission, '$') 
            ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>