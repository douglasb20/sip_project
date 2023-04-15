<?php

include_once __DIR__.'/../includes/default/defaultController.php';
include_once  __DIR__ . '/../api/telegramClass.php';



Class webhooksTelegram extends DefaultController{

    function __construct(){
        try{
           parent::__construct();
           $this->telegramClass = new TelegramClass($this->masterMysqli);
           $this->retorno = [
                                'codigo' => '',
                                'descricao' => '',
                                'dataHora' => '',
                            ];

        }catch(Exception $e){
            throw $e;
        }
    }

    public function salvarWebhook(){
        try {

            // Primeiro de tudo pego os dados e salvo o log

            $data = file_get_contents('php://input');

            if(empty($data)){
                throw new Exception("Requisição inválida.", 1);
            }

            $data = json_decode($data,true);
        
            $texto_log = "\n".date('Y-m-d H:i:s')." - Resposta Telegram. Dados: ".json_encode($data);
            $a=fopen(__DIR__.'/../logs/logWebhooksTelegram.log', 'a+');
            fwrite($a,$texto_log);
            fclose($a);
            
            $retorno = $this->telegramClass->trataRespostaWebhook($data);
            
            return $retorno;

        } catch (Exception $e) {
            throw $e;
        }
    }
}

try {

    $controller = new webhooksTelegram();

    $retorno = $controller->salvarWebhook();
    
    http_response_code($retorno['codigo']);
    echo json_encode($retorno);
    
} catch (Exception $e) {
    
    $data = file_get_contents('php://input');

    $texto_log = "\n".date('Y-m-d H:i:s')." - Webhook com exceção. Código: ".$e->getCode().". Mensagem: ".$e->getMessage()." Dados: ".json_encode($data);
    $a=fopen(__DIR__.'/../logs/webhooksTelegram.log', 'a+');
    fwrite($a,$texto_log);
    fclose($a);
    
    $retorno = [ "codigo" => 500,
                 "descricao"=> "NAO FOI POSSIVEL PROCESSAR A REQUISICAO. MOTIVO: ". $e->getMessage(),
                 "dataHora"=> date('Y-m-d H:i:s'),
    ];

    http_response_code(500);

    echo json_encode($retorno);
}

?>