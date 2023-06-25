<?php

namespace App\Classes;
use Symfony\Component\Config\FileLocator;
class SipClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\SipDAO $SipDAO;

    /**
    * Função para atualizar sip pelo arquivo config
    * @author Douglas A. Silva
    * @return void
    */
    public function UpdateSipsFromConfig(){
        try{
            
            $arquivo = ROOT_PATH . '/sip-extensions.conf';
            $config  = (new  \App\Classes\ConfiLoaderClass)->loadConfig($arquivo);

            foreach($config as $key => $val){
                if(!empty($val['callerid'])){
                    $bindSip[]  = [
                        "id_sip"     => $key,
                        "sip_dial"   => $key,
                        "callerId"   => $val['callerid'],
                        "id_empresa" => GetSessao("id_empresa")

                    ];
                }
            }
            $this->SipDAO->insertUpdateMultiplo($bindSip);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para adicionar ou atualizar SIP
    * @author Douglas A. Silva
    * @return void
    */
    public function SaveSip($dados){
        try{
            
            $bindSip  = [
                "id_sip"     => $dados['id_sip'],
                "sip_dial"   => $dados['id_sip'],
                "callerId"   => $dados['callerId'],
                "id_empresa" => GetSessao("id_empresa")
            ];
            
            $this->SipDAO->insertUpdate($bindSip);

        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para alterar satus do SIP
    * @author Douglas A. Silva
    * @return void
    */
    public function ToggleSipStatus(int $id_sip,int $sip_status, int $id_empresa){
        try{

            $this->SipDAO->update(["sip_status" => $sip_status], "id_sip = {$id_sip} AND id_empresa = {$id_empresa}");

        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>