<?php

namespace App\Controller;

class CallsPanelController extends Controller{
    /**
    * descricao
    * @author Douglas A. Silva
    * @return return
    */
    public function Index(){
        try{
            
            $this->render("CallsPanell");
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>