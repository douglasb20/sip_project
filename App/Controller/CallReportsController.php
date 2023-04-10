<?php

namespace App\Controller;

class CallReportsController extends Controller{

    public function Index(){
        try{
            
            $this
            ->setBreadcrumb(["Home", "Relatório de ligações"])
            ->render("CallReports");
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function CallReports(){
        try{
            $cdr = (new \App\Classes\CdrClass)->CallReports();
            $this->data = $cdr;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>