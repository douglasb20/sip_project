<?php

namespace App\Controller;

class CallReportsController extends Controller{

    public function Index(){
        try{
            $this->CheckSession();

            $dados['status'] = $this->StatusCallbackDAO->getAll();
            $dados['devices'] = (new \App\Services\CdrService)->GetDevices();

            $this
            ->setBreadcrumb(["Home", "Relatório de ligações"])
            ->renderTwig("CallReports", $dados);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function CallReports(){
        try{
            $this->CheckSession();

            $cdr        = (new \App\Classes\CdrClass)->CallReports($this->getPost());
            $this->data = $cdr;
            
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>