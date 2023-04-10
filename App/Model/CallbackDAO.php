<?php

namespace App\Model;

class CallbackDAO extends \Core\Defaults\DefaultModel{
    public $tabela = 'callback';

    public function ListaCallback(){
        try{
            $query = "  SELECT 
                            c.id, 
                            c.cpf_callback, 
                            c.numero_callback, 
                            c.data_callback,  
                            c.id_status_callback,
                            c.data_retornada,
                            c.operador_retornou,
                            sc.nome_status
                        FROM callback as c
                        INNER JOIN status_callback as sc ON sc.id_status = c.id_status_callback
                        WHERE c.id_status_callback = 1
                        ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>