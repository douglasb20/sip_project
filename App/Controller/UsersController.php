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
            $sip         = $this->SipDAO->getAll(" id_empresa = " . GetSessao("id_empresa"));

            $this
            ->setBreadcrumb(["Sistema", "Usuários"])
            ->renderTwig("System.Users", ["permissions" => $permissions, "sip" => $sip]);
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
            $input = $this->getPost();
            $id_empresa = GetSessao('id_empresa');

            extract($input);
            $where = "1=1";

            if( is_array( $user_sts ) ){
                $user_sts = implode("','",$user_sts);
                $where .= " AND user_sts in ('{$user_sts}')";
            }else{
                if($user_sts !== "-1"){
                    $where .= " AND user_sts = '{$user_sts}'";
                }
            }

            if(!empty($data_de)){
                $data_de  = \DateTime::createFromFormat('d/m/Y', $data_de)->format('Y-m-d');
                $data_ate = \DateTime::createFromFormat('d/m/Y', $data_ate)->format('Y-m-d');
                $where .= " AND DATE(user_lastlogin) BETWEEN '$data_de' AND '$data_ate' ";
            }

            $where .= " AND id_empresa={$id_empresa}";

            $users = $this->UsersDAO->getView($where);
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

            $id         = $this->getQuery("id");
            $id_empresa = GetSessao('id_empresa');

            $user       = $this->UsersDAO->getView(" id={$id} AND id_empresa = {$id_empresa}")[0];

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