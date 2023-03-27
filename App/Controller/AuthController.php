<?php

namespace App\Controller;

use App\Classes\UsersClass;

class AuthController extends Controller{

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

}