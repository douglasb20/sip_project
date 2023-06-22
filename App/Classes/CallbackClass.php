<?php

namespace App\Classes;

class CallbackClass extends \Core\Defaults\DefaultClassController{

    public \App\Model\CallbackDAO $CallbackDAO;

    public function AtualizaCallback($dados){
        try{
            extract($dados);

            $bindCallback = [
                "id_status_callback" => $id_status_callback,
                "operador_retornou" => GetSessao("ramal"),
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
            if(!isset($headers['Authorization']) ){
                return;
            }else{
                if($headers['Authorization'] != "Bearer kLs9rltwPF8cUXA7P33sAMFd0LbMgW"){
                    return;
                }
            }

            extract($dados);

            $bindCallback = [
                "cpf_callback"    => $cpf,
                "numero_callback" => $numero,
                "id_empresa"      => $id_empresa
            ];

            $this->CallbackDAO->insert($bindCallback);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    /**
    * Função para fazer baixa automatica de callback
    * @author Douglas A. Silva
    * @return void
    */
    public function BaixaCallback(){
        try{
            $cdr = new \App\Services\CdrService;

            echo "===================== " . date("d/m/Y H:i:s") . " =======================\n";
            echo "Buscando retornos\n";
            $callbacks = $this->CallbackDAO->getAll("id_status_callback = 1");
            
            if(!empty($callbacks)){
                $qtdCallback = count($callbacks);
                echo "Foram encontrados {$qtdCallback} pedidos de retornos.\n";
            }
            
            foreach($callbacks as $key => $call){
                $where  = "1=1";
                $where .= " AND (dst LIKE '%{$call['numero_callback']}%' OR src LIKE '%{$call['numero_callback']}%') ";
                $where .= " AND calldate > '{$call['data_callback']}' ";
                $where .= " AND status = 'ANSWERED' ";

                $retorno = $cdr->getView($where);

                if(!empty($retorno)){
                    echo "Houve um retorno para o cliente do número {$call['numero_callback']}.\n";
                    $retorno = end($retorno);
                    $status  = $retorno['dst'] === $call['numero_callback'] ? '2' : "3";
                    
                    $bindCallback = [
                        "id_status_callback"    => $status,
                        "data_retornada"        => $retorno['calldate'],
                        "operador_retornou"     => $status === "2" ? $retorno['src'] : $retorno['dstchannel']
                    ];
                    
                    echo "Dando baixa do retorno do número {$call['numero_callback']} \n";
                    $this->CallbackDAO->update($bindCallback, "id = {$call['id']}");

                }
            }
            echo "Finalizado com sucesso\n";
            echo "==========================================\n\n\n";
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>