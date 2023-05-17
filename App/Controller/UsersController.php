<?php

namespace App\Controller;
use App\Classes\UsersClass;

class UsersController extends Controller{

    public \App\Model\UsersDAO $UsersDAO;
    
    /**
    * Função Index
    * @author Douglas A. Silva
    * @return return
    */
    public function Index(){
        try{
            $this->CheckSession();
            
            $this
            ->setBreadcrumb(["Sistema", "Usuários"])
            ->render("System.users");
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

}

?>