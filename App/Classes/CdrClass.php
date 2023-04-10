<?php

namespace App\Classes;

class CdrClass extends \Core\Defaults\DefaultClassController{
    
    public function CallReports(){
        try{
            $cdr = (new \App\Services\CdrService)->CallReports();

            return $cdr;
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>