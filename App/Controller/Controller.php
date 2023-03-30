<?php

namespace App\Controller;

class Controller extends \Core\Defaults\DefaultController{

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
            }
        }catch(\Exception $e){
            throw $e;
        }
    }

}