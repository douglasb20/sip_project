<?php

namespace App\Controller;

class CallsPanelController extends Controller{
    public \App\Model\SipDAO $SipDAO;


    /**
    * descricao
    * @author Douglas A. Silva
    * @return return
    */
    public function Index(){
        try{
            $this->CheckSession();
            $dados = [] ;

            $dados["sip"] = $this->SipDAO->getAll();

            $this
            ->setClassDivContainer("container-fluid")
            ->setBreadcrumb(["PABX", "Painel de ligações"])
            ->render("Pabx.CallsPanel",$dados);
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>