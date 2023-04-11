<?php

namespace App\Controller;

class CallReportsController extends Controller{

    public function Index(){
        try{
            
            $dados['status'] = $this->StatusCallbackDAO->getAll();
            $dados['devices'] = (new \App\Services\CdrService)->GetDevices();

            $this
            ->setBreadcrumb(["Home", "Relatório de ligações"])
            ->render("CallReports", $dados);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function CallReports(){
        try{
            $cdr = (new \App\Classes\CdrClass)->CallReports($this->getPost());
            $this->data = $cdr;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>