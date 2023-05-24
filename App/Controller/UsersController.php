<?php

namespace App\Controller;

class UsersController extends Controller{

    public \App\Model\UsersDAO $UsersDAO;
    public \App\Model\UsersPermissionsXUsersDAO $UsersPermissionsXUsersDAO;
    public \App\Model\SipDAO $SipDAO;
    
    /**
    * Função Index
    * @author Douglas A. Silva
    * @return return
    */
    public function Index(){
        try{
            $this->CheckSession();

            $permissions = (new \App\Classes\UsersClass)->GetPermissions();
            $sip         = $this->SipDAO->getAll();

            $this
            ->setBreadcrumb(["Sistema", "Usuários"])
            ->render("System.Users", ["permissions" => $permissions, "sip" => $sip]);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function devChangePass(){
        try{
            
            $users = $this->UsersDAO->getAll();
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para retornar lista de usuários
    * @return array return
    */
    public function GetListUsers(){
        try{
            $this->CheckSession();

            $users = $this->UsersDAO->getView();
            $this->data = $users;
            $this->retorna();
        }catch(\Exception $e){
            $this->retorna($e);
        }
    }

    /**
    * Função para retornar usuário
    * @return array
    */
    public function GetUser(){
        try{
            $this->CheckSession();

            $id = $this->getQuery("id");

            $user = $this->UsersDAO->getView(" id={$id}")[0];

            $this->data = $user;
            $this->retorna();
        }catch(\Exception $e){
            $this->retorna($e);
        }
    }

    /**
    * Função para atualizar usuário
    * @return array
    */
    public function UpdateUser(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            $id    = $this->getQuery("id");
            $input = $this->getPost();

            (new \App\Classes\UsersClass)->UpdateUser($id, $input);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

    /**
    * Função para adicionar usuário
    * @return array
    */
    public function NewUser(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            $input = $this->getPost();

            (new \App\Classes\UsersClass)->NewUser($input);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

    /**
    * Função para pegar permissões do usuário
    * @return array
    */
    public function GetUserPermissions(){
        try{
            $this->CheckSession();

            $id = $this->getQuery("id");
            $permissions = $this->UsersPermissionsXUsersDAO->getAll(" id_user = '{$id}' ");
            $this->data = $permissions;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    
    /**
    * Função para salvar permissões do usuário
    * @return array
    */
    public function SaveUserPermissions(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            $id_user = $this->getQuery("id");
            $input   = $this->getPost();

            (new \App\Classes\UsersClass)->SaveUserPermissions($id_user, $input);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }
    
    /**
    * Função para alterar status do usuário
    * @return array
    */
    public function ToggleUserStatus(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            $id_user = $this->getQuery("id");
            $status  = $this->getQuery("status");

            (new \App\Classes\UsersClass)->ToggleUserStatus($id_user, $status);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

}

?>