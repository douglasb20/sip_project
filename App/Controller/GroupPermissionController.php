<?php

namespace App\Controller;

class GroupPermissionController extends Controller{

    public \App\Model\GroupPermissionDAO $GroupPermissionDAO;
    public \App\Model\GroupPermissionXPermissionDAO $GroupPermissionXPermissionDAO;
    public \App\Model\GroupPermissionXUserDAO $GroupPermissionXUserDAO;
    public \App\Model\UsersDAO $UsersDAO;

    public function Index(){
        try{
            $this->CheckSession();
            $permissions = (new \App\Classes\PermissionsClass)->GetPermissions();
            $users       = $this->UsersDAO->getAll("id_empresa = " . GetSessao("id_empresa"));

            $this
            ->setBreadcrumb(["Sistema", "Grupo de permissões"])
            ->render("System.GroupPermission", ["permissions" => $permissions, "users" => $users]);
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para gerar lista de grupo de permissões
    * @return array
    */
    public function GetListGroup(){
        try{
            $where = "1=1";

            $where .= " AND group_permission_status = 1" ;
            $where .= " AND id_empresa = " . GetSessao("id_empresa");

            $groups = $this->GroupPermissionDAO->getAll($where);

            $this->data = $groups;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para gerar lista de grupo de permissões
    * @return array
    */
    public function GetGroupForm(){
        try{
            $id_group = $this->getQuery("id_group");
            
            $permissions = $this->GroupPermissionXPermissionDAO->getAll("id_group_permission = {$id_group}");
            $users = $this->GroupPermissionXUserDAO->getAll("id_group_permission = {$id_group}");

            $this->data = [
                "permissions" => array_column($permissions, "id_permission"), 
                "users" => array_column($users, "id_user")
            ];
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    
    /**
    * Função para adicionar grupo de permissão
    * @return array
    */
    public function NewGroup(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();
            
            
            $input = $this->getPost();

            (new \App\Classes\GroupPermissionClass)->NewGroup($input);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }
    
    /**
    * Função para adicionar grupo de permissão
    * @return array
    */
    public function UpdateGroup(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            $id_group = $this->getQuery("id_group");
            $input = $this->getPut();

            (new \App\Classes\GroupPermissionClass)->UpdateGroup($id_group, $input);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }


}

?>