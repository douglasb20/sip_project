<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class BaixaCallbackCron extends \Core\Defaults\DefaultCronController{

    public function BaixaCallback(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            
            (new \App\Classes\CallbackClass)->BaixaCallback();

            $this->masterMysqli->commit();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }
}

try{
    (new BaixaCallbackCron)->BaixaCallback();
}catch(\Exception $e){
    throw $e;
}

?>