<?php

// considerando que va rodar chamando o php jogando arguementos como
// $ php addCallback.php cpf numero

$args = $argv;
array_shift($args) ;

$cpf = $args[0];
$numero = $args[1];

$id_empresa = 1;

$ch = curl_init();

$headers =  [
                'Authorization: Bearer kLs9rltwPF8cUXA7P33sAMFd0LbMgW'
            ];


curl_setopt($ch, CURLOPT_URL,"http://sip.lanteca.com.br/api/add_callback/{$cpf}/{$numero}/{$id_empresa}");
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resp = curl_exec($ch);

if(curl_error($ch)){
    echo curl_error($ch);
}else{
    echo "Solicitação feita com sucesso: " . $resp;
}

