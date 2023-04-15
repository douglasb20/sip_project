<?php

Class WebClientTelegram{

    private $token;
    private $url_base;
    private $ambiente;

    function __construct(){

        if(isset($_SERVER['USER']) && $_SERVER['USER']=='root'){  //cron
               // throw new Exception("Não funcionar em produção ainda.", -1);       
                    $this->token = "1596543788:AAF9ItHjNmEvkXHpCW3ibA92Urii6KISZ6w";
                    $this->ambiente = 'Produção';

        }
        else
            switch(getenv('HTTP_HOST')){
                case 'portal.cedet.com.br':
                case 'portal-intra.cedet.com.br':
                    $this->token        = '1596543788:AAF9ItHjNmEvkXHpCW3ibA92Urii6KISZ6w';
                    $this->ambiente     = 'Produção';
                break;

                default: 
                    $this->token        = '1603191023:AAHu0INVAKhGYsoR92K4eSf2TMukqcy1T-Y';
                    $this->ambiente     = 'Homologação';
                break;
            }

        $this->url_base = 'https://api.telegram.org/bot'.$this->token;

    }

    private function executaRequisicao($link,$tipo_requisicao,object| array | bool $post_fields=false){
        try {
            $ch = curl_init();
            
            switch ($tipo_requisicao) {
                case 'get': break;

                case 'post':
                    //$headers[] = 'content-type: application/json';
                    if($post_fields){
                        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_fields));
                    }
                    break;
                default:
                    throw new Exception("Tipo requisição inválida", 1);
                    break;
            }

            curl_setopt($ch, CURLOPT_URL, $link);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $conteudo = curl_exec($ch);

            if (curl_error($ch)){
                throw new Exception("Erro na requisição ".$tipo_requisicao." do Telegram: " . curl_error($ch), 1);
            }

            if( !json_decode( $conteudo )->ok ){
                $aditional = '';
                if(strpos(json_decode( $conteudo )->description, 'chat not found')){
                    $aditional = 'Chat_id: '. $post_fields['chat_id'].' em '. $this->ambiente. ' ';
                }
                throw new Exception("Erro na requisição do Telegram: ". $aditional .json_decode( $conteudo )->description, 1);
            }

            return $conteudo;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function sendMessage ($chatId, $message) {
        try{
            $url = $this->url_base."/sendMessage";

            $fields = [
                'chat_id'       => $chatId,
                'text'          => $this->noTags( $message ) ,
                'parse_mode'    => 'html',
            ];
            return json_decode($this->executaRequisicao($url, 'post', $fields), true);
        } catch (Exception $e) {
            throw $e;
        }
    
    }

    public function noTags($string){
    
        $no_tags = ['<br>', '<br />', '<br/>'];
    
        return str_replace($no_tags, PHP_EOL , $string);
    }

}


?>