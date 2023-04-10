<?php

namespace App\Classes;

class CallbackClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\CallbackDAO $CallbackDAO;

    public function AtualizaCallback($dados){
        try{
            extract($dados);

            $bindCallback = [
                "id_status_callback" => $id_status_callback,
                "operador_retornou" => getSessao("ramal"),
                "data_retornada" => date("Y-m-d H:i:s")
            ];

            $this->CallbackDAO->update($bindCallback, "id = {$id_callback}");

            return count($this->CallbackDAO->getAll(" id_status_callback = 1"));
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AddCallback($dados){
        try{
            $headers = getallheaders();
            if(!isset($headers['Authorization']) || !$headers['Authorization'] == "Bearer kLs9rltwPF8cUXA7P33sAMFd0LbMgW"){
                return;
            }

            extract($dados);

            $bindCallback = [
                "cpf_callback" => $cpf,
                "numero_callback" => $numero
            ];

            $this->CallbackDAO->insert($bindCallback);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>