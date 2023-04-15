<?php

    require_once __DIR__ . '/../includes/PHPMailer-new/PHPMailerAutoload.php';

    Class PhpMailerPortal Extends PHPMailer{

        public function __construct($smtp=null,$show_exception = false){
            try{
                parent::__construct($show_exception);

                if($smtp!=null){
                    $this->CharSet='UTF-8';
                    $this->IsSMTP (); // set mailer to use SMTP
                    $this->Host = $smtp['host']['value']; // specify main and backup server
                    $this->Port = $smtp['port']['value'];
                    $this->SMTPAuth = $smtp['SMTPAuth']['value']; // turn on SMTP authentication
                    $this->SMTPSecure = $smtp['SMTPSecure']['value'];
                    $this->Username = $smtp['user']['value']; // SMTP username
                    $this->Password = $smtp['password']['value']; // SMTP password
                    $this->From = $smtp['frommail']['value'];
                    $this->FromName = $smtp['fromname']['value'];

                    if(isset($smtp['replytomail']['value'])){
                        $this->AddReplyTo( $smtp['replytomail']['value'], $smtp['replytoname']['value'] );
                    }else{
                        $this->AddReplyTo( $smtp['frommail']['value'], $smtp['fromname']['value'] );
                    }               
                    $this->IsHTML( $smtp['IsHTML']['value'] );

                    $this->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                }
               

            }catch(Exception $e){
                throw $e;
            }
        }

        public function AddAddressGrupo($grupo, $assunto) {
            try{
          
                foreach ($grupo as $key=> $value){

                    $this->AddBCC($value['email'],$assunto);
                }

            }catch(Exception $e){
                throw $e;
            }

        }       

        public function trocaVars ($msg, $arrayEnv) {
            foreach ($arrayEnv as $id=> $var){
                $msg = str_replace($id, $var, $msg);
            }
             return $msg;
        }       
    }
?>