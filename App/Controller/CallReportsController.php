<?php

namespace App\Controller;

class CallReportsController extends Controller{

    public \App\Model\SystemConfigDAO $SystemConfigDAO;

    public function Index(){
        try{
            $this->CheckSession();

            $dados['status'] = $this->StatusCallbackDAO->getAll();
            $dados['devices'] = (new \App\Services\CdrService)->GetDevices();

            $this
            ->setBreadcrumb(["Home", "Relatório de ligações"])
            ->render("CallReports", $dados);
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

    /**
    * Função para pegar ligacao
    * @author Douglas A. Silva
    * @return array
    */
    public function ListenRecorded(){
        try{
            $system    = $this->SystemConfigDAO->getOne("keyword = 'records_path'");
            $path      = str_replace("SISTEMA",ROOT_PATH, $system['value']);

            $protocolo = $this->getQuery("protocolo");

            $cdr = (new \App\Services\CdrService)->getView(" protocolo = {$protocolo}")[0];
            if( empty($cdr['monitor'])){
                throw new \Exception("Ligação não tem gravação", -1);
            }
            $file_path = $path. "/". $cdr['monitor'];

            if(!file_exists($file_path)){
                throw new \Exception("Gravação não encontrada", -1);
            }
            $file = file_get_contents($file_path);

            $data = [
                "name" => $cdr['monitor'],
                "audio" => "data:audio/wav;base64," . base64_encode($file)
            ];

            $this->data = $data;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>