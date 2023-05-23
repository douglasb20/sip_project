<?php

namespace App\Model;

class UsersPermissionsDAO extends \Core\Defaults\DefaultModel{
    public $tabela = 'users_permissions';

    public function GetCategories(){
        try{
            $query = " SELECT 
                            category
                        FROM
                            users_permissions
                        GROUP BY category";

            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>