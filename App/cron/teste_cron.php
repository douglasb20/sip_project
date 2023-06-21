<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Teste extends \Core\Defaults\DefaultCronController{

    public function funcao(){
        try{
            return "Douglas";
        }catch(\Exception $e){
            throw $e;
        }
    }

}

try{
    printar((new Teste)->funcao());
}catch(\Exception $e){
    throw $e;
}