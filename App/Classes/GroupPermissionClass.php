<?php

namespace App\Classes;

class GroupPermissionClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\UsersPermissionsDAO $UsersPermissionsDAO;
    public \App\Model\GroupPermissionDAO $GroupPermissionDAO;
    public \App\Model\GroupPermissionXUserDAO $GroupPermissionXUserDAO;
    public \App\Model\GroupPermissionXPermissionDAO $GroupPermissionXPermissionDAO;
    
    /**
    * Função para adicionar um grupo de permissão
    * @author Douglas A. Silva
    * @return void
    */
    public function NewGroup($dados){
        try{
            extract($dados);

            $bindGrupo = [
                "group_permission_description" => $nome_group,
                "id_empresa" => GetSessao("id_empresa")
            ];

            $id_group = $this->GroupPermissionDAO->insert($bindGrupo);

            foreach($group_users as $user){

                $bindUserPermission = [
                    "id_group_permission" => $id_group,
                    "id_user"             => $user
                ];
                $this->GroupPermissionXUserDAO->insertUpdate($bindUserPermission);
                
                $this->SaveGroupPermissions($id_group, $permissions);
                (new PermissionsClass)->SaveUserPermissions($user, ["permissions"=> $permissions]);
            }

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para atualizar um grupo de permissão
    * @author Douglas A. Silva
    * @return void
    */
    public function UpdateGroup($id_group, $dados){
        try{
            extract($dados);

            $bindGrupo = [
                "group_permission_description" => $nome_group,
            ];

            $this->GroupPermissionDAO->update($bindGrupo, "id_group_permission = {$id_group}");
            $this->GroupPermissionXUserDAO->delete("id_group_permission = {$id_group}");

            foreach($group_users as $user){

                $bindUserPermission = [
                    "id_group_permission" => $id_group,
                    "id_user"             => $user
                ];
                $this->GroupPermissionXUserDAO->insertUpdate($bindUserPermission);
                
                $this->SaveGroupPermissions($id_group, $permissions);
                (new PermissionsClass)->SaveUserPermissions($user, ["permissions"=> $permissions]);
            }

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
    public function SaveGroupPermissions(int $id_group, array $permissions){
        try{

            $this->GroupPermissionXPermissionDAO->delete(" id_group_permission = '{$id_group}'");
            $same = $this->UsersPermissionsDAO->getAll( "  same_as is not null");
            foreach($permissions as $v){

                $bindGroupPermission[] = [
                    "id_group_permission" => $id_group,
                    "id_permission"       => $v
                ];

                foreach($same as $s){
                    if($s['same_as'] === $v){
                        $bindGroupPermission[] = [
                            "id_group_permission" => $id_group,
                            "id_permission"       => $s['id'],
                        ];
                    }
                }
            }

            $this->GroupPermissionXPermissionDAO->insertMultiplo($bindGroupPermission);

        }catch(\Exception $e){
            throw $e;
        }
    }

    
    /**
    * Função para associar usuario a um grupo
    * @author Douglas A. Silva
    * @return void
    */
    public function AssocUserToGroup($id_group, $id_user){
        try{
            

            if(!empty($id_group)){
                $bindUserPermission = [
                    "id_group_permission" => $id_group,
                    "id_user"             => $id_user
                ];
                $this->GroupPermissionXUserDAO->insertUpdate($bindUserPermission);
            }else{
                $this->GroupPermissionXUserDAO->delete("id_user = {$id_user}");
            }
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>