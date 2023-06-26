<?php

require_once __DIR__ . '/vendor/autoload.php';

try{
    
    $m = [
        "host"     => "mail.lantecatelecom.com.br",
        "port"     => 587,
        "SMTPAuth" => true,
        "user"     => "no-reply@ltcfibra.com.br",
        "password" => $_ENV['PASSWORD_EMAIL'],
        "frommail" => "no-reply@ltcfibra.com.br",
        "fromname" => "LTC Fibra",
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

