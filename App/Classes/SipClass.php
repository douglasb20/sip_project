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
            
            $loader  = new  \App\Classes\ConfiLoaderClass(new FileLocator(ROOT_PATH));
            $config  = $loader->load('sip_additional.conf');
            $bindSip = [];

            foreach($config as $key => $val){
                if(!empty($val['callerid'])){
                    $bindSip[]  = [
                        "id_sip" => $key,
                        "sip_dial" => $key,
                        "callerId" => preg_replace("/\s?<\d+>/", "", $val['callerid'])
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
                "id_sip"   => $dados['id_sip'],
                "sip_dial" => $dados['id_sip'],
                "callerId" => $dados['callerId']
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
    public function ToggleSipStatus(int $id_sip,int $sip_status){
        try{

            $this->SipDAO->update(["sip_status" => $sip_status], "id_sip = {$id_sip}");

        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>