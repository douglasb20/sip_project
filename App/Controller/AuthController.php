<?php

namespace App\Controller;

use App\Classes\UsersClass;

class AuthController extends Controller{

    public \App\Model\UsersDAO $UsersDAO;

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
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            extract($this->getPost("username"));

            (new UsersClass)->AuthenticateLoginUser($username, $password);
            
            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
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

    /**
    * Função para solicitar nova senha
    * @author Douglas A. Silva
    * @return void
    */
    public function ForgotPassword(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $user_email = $this->getQuery("user_email");
            (new UsersClass)->ForgotPassword($user_email);
            
            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

    /**
    * Função para criar nova senha
    * @author Douglas A. Silva
    * @return void
    */
    public function RecoverPassword(){
        try{
            $dados = [
                "status" => true,
                "msg"    => "",
                "dados"  => []
            ];

            $user_token = decrypt($this->getQuery("forgot_token"));

            if(empty($user_token)){
                $dados = [
                    ...$dados,
                    "status" => false,
                    "msg" => "Erro do token"
                ];
            }
            
            $user_token     = json_decode($user_token, true);
            $user           = $this->UsersDAO->getOne(" id = '{$user_token['id']}'");
            $dados["dados"] = ["id" => $user['id']];


            $this
            ->setShowMenu(false)
            ->setTituloPagina("Recuperação de senha")
            ->setClassDivContainer("container d-flex justify-content-center align-items-start pt-5 h-100")
            ->render("Login.recoverPassword", $dados);
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para criar nova senha
    * @author Douglas A. Silva
    * @return void
    */
    public function RequestRecover(){
        try{
            extract($this->getPost());
            $id = $this->getQuery("id_user");
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            
            (new \App\Classes\UsersClass)->RequestRecover($id,$password,$confirm_password);

            $this->masterMysqli->commit();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

}