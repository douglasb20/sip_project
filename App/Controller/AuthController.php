<?php

namespace App\Controller;

use App\Classes\UsersClass;
use Exception;

class AuthController extends Controller{

    public function Index(){
        try{
            if($this->validateAuth()){
                route()->redirect("home");
                return;
            }

            $this->setShowMenu(false)
            ->setTituloPagina("SIP Lanteca")
            ->setClassDivContainer("container d-flex justify-content-center align-items-center h-100")
            ->render("Login");
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function Authentication(){
        try{
            
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $inputJSON = $this->getPost();

            if(!empty($inputJSON)){
                extract($inputJSON); 
            }else{
                throw new \Exception("Parametros `email` e `password` não encontrados",-1);
            }

            if(!isset($email) || !isset($password)){
                throw new \Exception("Faltando parametros email e/ou password.",-1);
            }

            $token = (new UsersClass)->AuthenticateUser($email, $password);
            
            $data = [
                "token" => $token
            ];
            $this->masterMysqli->commit();
            $this->data = $data;
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            http_response_code(401);
            throw $e;
        }
    }

    public function AuthLogin(){
        try{
            $data = $this->getPost();
            $ret  = (new UsersClass)->AuthenticateLoginUser($data['username'], $data['password']);
            
            $this->data = $ret;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function ValidateLogin(){
        try{
            $data = $this->getPost();
            
            SetSessao("autenticado", true);
            SetSessao("ramal", $data['ramal']);
            SetSessao("lifetime", date('Y-m-d H:i:s', strtotime('+6 hours')) );
            
            // $this->data = $ret;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AuthLogout(){
        try{
            $this->setShowMenu(false);
            clearSessao();
            route()->redirect("login");
        }catch(\Exception $e){
            throw $e;
        }
    }

}