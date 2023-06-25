<?php

require_once __DIR__ . '/vendor/autoload.php';

try{
    
    $m = [
        "host"     => "smtp.gmail.com",
        "port"     => "587",
        "SMTPAuth" => true,
        "user"     => "douglaassgenesis@gmail.com",
        "password" => "lrxbasicdtjhhgbu",
        "frommail" => "douglaassgenesis@gmail.com",
        "fromname" => "Douglas A. Silva",
        "tomail"   => "douglas.silva@atendecerto.com.br",
        "toname"   => "Douglas Atende",
        "IsHTML"   => true,
    ];
    
    $mail = new \App\Services\PhpMailerPortal($m);
    $mail->Subject = "Teste de Email";
    $mail->Body = "Estou testando envio de mensagem";
    $mail->send();
}catch(\Exception $e){
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

