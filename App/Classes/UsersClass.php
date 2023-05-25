<?php

namespace App\Classes;

use Exception;
use Firebase\JWT\JWT;

class UsersClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\UsersDAO $UsersDAO;
    public \App\Model\UsersPermissionsDAO $UsersPermissionsDAO;
    public \App\Model\UsersPermissionsXUsersDAO $UsersPermissionsXUsersDAO;

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

    public function AuthenticateLoginUser($user, $password){
        try{
            $user = $this->UsersDAO->getAll(" user_login = '".strtoupper( $user)."' "  );

            if(empty($user)){
                throw new \Exception("Usuário não encontrado.",401);
            }

            $user = $user[0];

            if(!password_verify( $password, $user['user_pass'] )){
                throw new \Exception("Senha não confere.",401);
            }

            if($user['user_sts'] === "2"){
                throw new \Exception("Usuário inativo.", -1);
            }

            // SetSessao("autenticado", true);
            SetSessao("id_usuario", $user['id']);
            SetSessao("nome_usuario", $user['user_fullname']);
            SetSessao("autenticado", true);
            SetSessao("ramal", $user['id_sip']);
            SetSessao("lifetime", date('Y-m-d H:i:s', strtotime('+6 hours')) );

            return (new \App\Services\CdrService)->GetDevices();
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

    /**
    * Função para atualizar dados do usuário
    * @author Douglas A. Silva
    * @param int $id Id do Usuário
    * @param array $dados Dados do form que irá atualizar do usuário
    * @return void
    */
    public function UpdateUser(int $id, array $dados){
        try{
            extract($dados);

            

            $user_login    = strtoupper($user_login);
            $user_nome     = strtoupper($user_nome);
            $user_lastname = strtoupper($user_lastname);
            $user_email    = strtolower($user_email);

            $user = $this->UsersDAO->getAll(" user_login = '{$user_login}'");

            if(!empty($user)){
                $user = $user[0];
                if($user['id'] !== $id){
                    throw new Exception("Já existe um usuário com este login.",-1);
                }
            }

            $user_fullname = "{$user_nome} {$user_lastname}";

            $bindUser = [
                "user_fullname" => $user_fullname,
                "user_login"    => $user_login,
                "user_nome"     => $user_nome,
                "user_email"    => $user_email,
                "id_sip"        => empty($id_sip) ? null : $id_sip,
                "user_passres"  => 0,
                "user_sts"      => 1,
            ];

            if( !empty($user_pass) ){
                $bindUser['user_pass'] = password_hash($user_pass, PASSWORD_BCRYPT);
            }

            $this->UsersDAO->update($bindUser, "id = {$id}");

            SetSessao("ramal", $id_sip);

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para adicionar dados do usuário
    * @author Douglas A. Silva
    * @param array $dados Dados do form que irá adicionar do usuário
    * @return void
    */
    public function NewUser(array $dados){
        try{
            extract($dados);

            $user_login    = strtoupper($user_login);
            $user_nome     = strtoupper($user_nome);
            $user_lastname = strtoupper($user_lastname);
            $user_email    = strtolower($user_email);

            $user_fullname = "{$user_nome} {$user_lastname}";

            $bindUser = [
                "user_fullname" => $user_fullname,
                "user_login"    => $user_login,
                "user_nome"     => $user_nome,
                "user_email"    => $user_email,
                "id_sip"        => $id_sip,
                "user_pass"     => password_hash($user_pass, PASSWORD_BCRYPT),
                "user_passres"  => 0,
                "user_sts"      => 1,
            ];

            $this->UsersDAO->insert($bindUser);

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para adicionar dados do usuário
    * @author Douglas A. Silva
    * @param array $dados Dados do form que irá adicionar do usuário
    * @return void
    */
    public function GetPermissions(){
        try{
            $permissions = $this->UsersPermissionsDAO->getAll();
            $categorias  = $this->UsersPermissionsDAO->GetCategories();

            $list = [];

            foreach($categorias as $key => $v){
                $list[$v['category']] = [];

                foreach($permissions as $key => $perm){
                    if($perm['category'] === $v['category']){
                        $list[$v['category']][] = [
                            "id"               => $perm['id'],
                            "permission_label" => $perm['permission_label'],
                            "type" => $perm['type'],
                        ];
                    }
                }
            }

            return $list;
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
    public function SaveUserPermissions(int $id_user, array $dados){
        try{

            $this->UsersPermissionsXUsersDAO->delete(" id_user = '{$id_user}'");
            foreach($dados['permissions'] as $v){
                $bindUserPermission[] = [
                    "id_permission" => $v,
                    "id_user"       => $id_user
                ];
            }

            $this->UsersPermissionsXUsersDAO->insertMultiplo($bindUserPermission);

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função alterar status do usuário
    * @author Douglas A. Silva
    * @param int $id_user id do usuário que irá alterar 
    * @param int $status status que será alterado
    * @return void
    */
    public function ToggleUserStatus(int $id_user, int $status){
        try{

            $this->UsersDAO->update(["user_sts" => $status], "id = {$id_user}");

        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>