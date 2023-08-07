<?php

namespace App\Classes;

class PermissionsClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\UsersPermissionsDAO $UsersPermissionsDAO;
    public \App\Model\UsersPermissionsXUsersDAO $UsersPermissionsXUsersDAO;
    
    /**
    * Função para adicionar dados do usuário
    * @author Douglas A. Silva
    * @param array $dados Dados do form que irá adicionar do usuário
    * @return void
    */
    public function GetPermissions(){
        try{
            $permissions = $this->UsersPermissionsDAO->getAll();
            $categorias  = $this->UsersPermissionsDAO->GetCategories();

            $list = [];

            foreach($categorias as $key => $v){
                $list[$v['category']] = [];

                foreach($permissions as $key => $perm){
                    if($perm['category'] === $v['category']){
                        $list[$v['category']][] = [
                            "id"               => $perm['id'],
                            "permission_label" => $perm['permission_label'],
                            "type" => $perm['type'],
                        ];
                    }
                }
            }

            return $list;
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para salvar permissões do usuário
    * @author Douglas A. Silva
    * @param int $id_user id do usuário que irá alterar as permissões
    * @param array $dados Dados do form que irá adicionar as permissões
    * @return void
    */
    public function SaveUserPermissions(int $id_user, array $dados){
        try{

            $this->UsersPermissionsXUsersDAO->delete(" id_user = '{$id_user}'");
            $same = $this->UsersPermissionsDAO->getAll( "  same_as is not null");
            foreach($dados['permissions'] as $v){

                $bindUserPermission[] = [
                    "id_permission" => $v,
                    "id_user"       => $id_user
                ];

                foreach($same as $s){
                    if($s['same_as'] === $v){
                        $bindUserPermission[] = [
                            "id_permission" => $s['id'],
                            "id_user"       => $id_user
                        ];
                    }
                }
            }

            $this->UsersPermissionsXUsersDAO->insertMultiplo($bindUserPermission);

        }catch(\Exception $e){
            throw $e;
        }
    }


}

?>