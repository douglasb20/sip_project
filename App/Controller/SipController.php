<?php

namespace App\Controller;

class SipController extends Controller{

    public function Index(){
        try{
            
            $this->CheckSession();

            $permissions = (new \App\Classes\UsersClass)->GetPermissions();

            $this
            ->setBreadcrumb(["Sistema", "Operadores"])
            ->render("System.Sip", ["permissions" => $permissions]);
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>