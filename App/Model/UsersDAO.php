<?php

namespace App\Model;

class UsersDAO extends \Core\Defaults\DefaultModel{
    public $tabela = 'users';

    public function ValidateUser($id){
        try{
            
            $query = "SELECT * FROM {$this->tabela} WHERE";
            $query .= " id = {$id} ";

            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>