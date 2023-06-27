<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

Class PhpMailerSystem Extends PHPMailer{

    public function __construct($smtp=null,$show_exception = true){
        try{
            parent::__construct($show_exception);

            if($smtp!=null){
                $this->IsSMTP (); // set mailer to use SMTP
                $this->CharSet    = 'UTF-8';
                $this->Host       = $smtp['host']; // specify main and backup server
                $this->Port       = $smtp['port'];
                $this->SMTPAuth   = $smtp['SMTPAuth']; // turn on SMTP authentication
                $this->SMTPSecure = 'tls';
                $this->Username   = $smtp['user']; // SMTP username
                $this->Password   = $smtp['password']; // SMTP password
                $this->setFrom($smtp['frommail'],$smtp['fromname']);
                $this->addAddress($smtp['tomail'], $smtp['toname']);

                if(isset($smtp['replytomail'])){
                    $this->AddReplyTo( $smtp['replytomail'], $smtp['replytoname'] );
                }else{
                    $this->AddReplyTo( $smtp['frommail'], $smtp['fromname'] );
                }               
                $this->IsHTML( $smtp['IsHTML'] );

                // $this->Subject = 'Here is the subject';
                // $this->Body    = 'This is the HTML message body <b>in bold!</b>';

                $this->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
            

        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AddAddressGrupo($grupo, $assunto) {
        try{
        
            foreach ($grupo as $key=> $value){

                $this->AddBCC($value['email'],$assunto);
            }

        }catch(\Exception $e){
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