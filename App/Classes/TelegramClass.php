<?php

include_once  __DIR__.'/../includes/model/configDAO.php';
include_once  __DIR__.'/../includes/model/telegramMensagensDAO.php';
include_once  __DIR__.'/../includes/model/telegramMensagensErroDAO.php';
require_once __DIR__.'/../includes/PHPMailer-new/PHPMailerAutoload.php';

include_once __DIR__.'/webClientTelegram.php'; 

Class TelegramClass extends DefaultController{
    private $canal_TI;

    function __construct($masterMysqli)
    {
        try{
             //Inicio ou reseto a Global de profiler de sql 
            if(!isset($_DB_PROFILER)){
                global $_DB_PROFILER;
            }

            $this->masterMysqli                 = $masterMysqli;

            $this->configDAO                    = new ConfigDAO($this->masterMysqli);
            $this->telegramMensagensDAO         = new TelegramMensagensDAO($this->masterMysqli);
            $this->telegramMensagensErroDAO     = new TelegramMensagensErroDAO($this->masterMysqli);
            $this->telegram                     = new WebClientTelegram(); 

            switch(getenv('HTTP_HOST')){
                case 'portal.cedet.com.br':
                case 'portal-intra.cedet.com.br':
                    $this->canal_TI     = '-1001432212957';
                break;
                default: 
                    if(isset($_SERVER['USER']) && $_SERVER['USER']=='root'){  //cron
                        $this->canal_TI     = '-1001432212957';
                    }else{
                        $this->canal_TI     = '-1001472350831';
                    }      
                break;
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    function __destruct() {
        // O mysqli está sendo fechado no controller que chama a classe , precisa usar isso caso for extender do defaultController
    }

    public function trataRespostaWebhook($resposta){
        try{
            $ret = [
                'codigo'    => '200',
                'descricao' => 'Processado com sucesso',
                'dataHora'  => date('Y-m-d H:i:s'),     
            ];

            $this->telegram->sendMessage($this->canal_TI, 'Webhook');
            return $ret;

        }catch (Exception $e) {
            throw $e;
        }
    }
    public function mensagemAlerta($mensagem){
        try{
            
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $mensagem = str_replace('<>','!=', $mensagem);
            $existe = $this->telegramMensagensDAO->getAll("chat_id =".$this->canal_TI . " AND mensagem = '".$mensagem."'" );
            
            if( empty( $existe ) ){
                $this->telegramMensagensDAO->insert( ['chat_id' => $this->canal_TI , 'mensagem' => $mensagem] );
                $this->telegram->sendMessage($this->canal_TI, $mensagem);
            }
            $this->masterMysqli->commit();
        }catch (Exception $e) {

            $this->masterMysqli->rollback();
            
            $bindMensagem = [
                'chat_id' => $this->canal_TI ,
                'mensagem' => $mensagem, 
                'mensagem_erro' => $e->getMessage(),
            ];
            $this->telegramMensagensErroDAO->insert( $bindMensagem );
            throw new Exception("Erro no telegram: ". $e->getMessage());
        }
    }

}
?>