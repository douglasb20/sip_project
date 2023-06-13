<?php

namespace App\Controller;

class Controller extends \Core\Defaults\DefaultController{

    public \App\Model\StatusCallbackDAO $StatusCallbackDAO;

    public function errorPage(){
        try{
            $this->setTituloPagina("Página inicial");
            $this->setClassDivContainer("container-fluid p-0");
            $this->setShowMenu(false);
            
            $this->render("404");
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function CheckSession(){
        try{
            if(!$this->validateAuth()){
                route()->redirect("login");
                die();
            }
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function StatusCallbackSelect(){
        try{
            $ret = $this->StatusCallbackDAO->getAll(" id_status != 1");
            $select = [];

            foreach($ret as $r){
                $select[] = [
                    "id" => $r['id_status'],
                    "text" => $r['nome_status']
                ];
            }

            return $select;
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AddCallback(){
        try{
            (new \App\Classes\CallbackClass)->AddCallback($this->getQuery());

            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

}