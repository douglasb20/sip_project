<?php

namespace App\Controller;

class Controller extends \Core\Defaults\DefaultController{

    public function errorPage(){
        try{
            $this->setTituloPagina("Página inicial");
            $this->setClassDivContainer("container-fluid p-0");
            
            $this->render("404");
        }catch(\Exception $e){
            throw $e;
        }
    }
    public function index(){
        try{
            $this->setShowMenu(true);
            $this->render("Home");
        }catch(\Exception $e){
            throw $e;
        }
    }

}