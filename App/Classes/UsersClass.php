<?php

namespace App\Classes;
use Firebase\JWT\JWT;

class UsersClass extends \Core\Defaults\DefaultClassController{

    public function ValidateUser($id){
        try{
            $user = $this->UsersDAO->ValidateUser($id);
            
            if(empty($user)){
                throw new \Exception("Usuário não validado", 401);
            }
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AuthenticateUser($email, $password){
        try{
            date_default_timezone_set("America/Sao_Paulo");

            $user = $this->UsersDAO->getAll(" user_email = '".strtolower( $email)."' "  );

            if(empty($user)){
                throw new \Exception("Usuário não encontrado.",401);
            }

            $user = $user[0];

            if(!password_verify( $password, $user['user_sys_pass'] )){
                throw new \Exception("Senha não confere.",401);
            }

            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

            $token = [
                "iss"        => $actual_link,
                "aud"        => $actual_link,
                "sub"        => $user['id'],
                "id"         => $user['id'],
                "name"       => $user['user_nome'],
                "fullname"   => $user['user_fullname'],
                "email"      => $user['user_email'],
                "reseted"    => $user['user_passres'],
                "last_login" => $user['user_lastlogin'],
                "iat"        => time(),
                "exp"        => (time() +  ((60 * 60) * 2))  // numero 2 é a quantidade de horas que irá expirar
            ];

            $this->UsersDAO->update(["user_lastlogin" => date("Y-m-d H:i:s")], "id =".$user['id']);

            return JWT::encode($token, $_ENV['KEY_JWT'], 'HS256');

        }catch(\Exception $e){
            throw $e;
        }
    }

    public function ValidaEmailUser($email){
        try{
            $user = $this->UsersDAO->getAll(" user_email = '{$email}'");
            if(count($user) > 0){
                throw new \Exception("Email já existe, tente outro email." ,-1);
            }
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>