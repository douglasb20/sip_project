<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class BaixaCallbackCron extends \Core\Defaults\DefaultCronController{

    public function Baixa(){
        try{
            $cdr = new \App\Services\CdrService;

            

        }catch(\Exception $e){
            throw $e;
        }
    }
}

try{
    (new BaixaCallbackCron)->Baixa();
}catch(\Exception $e){
    throw $e;
}

?>