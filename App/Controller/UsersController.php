<?php

namespace App\Controller;
use App\Classes\UsersClass;
class UsersController extends Controller{

    public function devChangePass(){
        try{
            
            $users = $this->UsersDAO->getAll();
            
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>