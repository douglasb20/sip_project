<?php

namespace App\Controller;

class SipController extends Controller{

    public \App\Model\SipDAO $SipDAO;

    public function Index(){
        try{
            $this->CheckSession();

            $this
            ->setBreadcrumb(["Sistema", "Operadores"])
            ->render("System.Sip");
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para listar todos os sips
    * @author Douglas A. Silva
    * @return array
    */
    public function GetSipList(){
        try{
            $this->CheckSession();
            $sip = $this->SipDAO->getAll();

            $this->data = $sip;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para atualizar sips pelo arquivo config
    * @author Douglas A. Silva
    * @return void
    */
    public function UpdateSipsFromConfig(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            (new \App\Classes\SipClass)->UpdateSipsFromConfig();

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

    /**
    * Função para adicionar ou atualizar o sip
    * @author Douglas A. Silva
    * @return void
    */
    public function SaveSip(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();
            $input = $this->getPost();
            (new \App\Classes\SipClass)->SaveSip($input);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

    /**
    * Função para adicionar ou atualizar o sip
    * @author Douglas A. Silva
    * @return void
    */
    public function ToggleSipStatus(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $this->CheckSession();

            $id_sip     = $this->getQuery("id_sip");
            $sip_status = $this->getQuery("sip_status");

            (new \App\Classes\SipClass)->ToggleSipStatus($id_sip,$sip_status);

            $this->masterMysqli->commit();
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

}

?>