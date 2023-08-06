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
            $permissions = (new \App\Classes\UsersClass)->GetPermissions();
            $users = $this->UsersDAO->getAll("id_empresa = " . GetSessao("id_empresa"));

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
}

?>