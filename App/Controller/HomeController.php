<?php

namespace App\Controller;

class HomeController extends Controller{

    public \App\Model\CallbackDAO $CallbackDAO;
    public \App\Model\StatusCallbackDAO $StatusCallbackDAO;

    public function Index(){
        try {
            $this->CheckSession();
            
            $cdr             = (new \App\Services\CdrService);
            $dadosGraf       = $cdr->GeraDadosGraficoHora();
            $dataGraf        = $cdr->GeraDadosGraficoData();
            
            $where           = " DATE(calldate) = CURDATE() ";
            $whereRealizadas = " {$where} and src IN (SELECT id_sip FROM sip_lanteca.sip)";
            $realizadas      = $cdr->getAll($whereRealizadas, "calldate desc");
            $realizadasCount = count($realizadas);
            $reports         = [];
            $byDate          = [];

            if(!empty($dadosGraf)){
                $reports = [
                    [
                        "name" => "Atentidas",
                        "data" => $dadosGraf['ANSWERED']['data']
                    ],
                    // [
                    //     "name" => "Ocupadas",
                    //     "data" => $dadosGraf['BUSY']['data']
                    // ],
                    [
                        "name" => "Perdidas",
                        "data" => $dadosGraf['NO_ANSWER']['data']
                    ],
                ];
            }

            if(!empty($dataGraf)){
                $byDate = [
                    [
                        "name" => "Perdidas",
                        "data" => $dataGraf['NO ANSWER']['data']
                    ],
                    [
                        "name" => "Atentidas",
                        "data" => $dataGraf['ANSWERED']['data']
                    ]
                ];
            }

            $dados               = $cdr->GetDataDashboard($where);

            $horas               = isset($dadosGraf['HORA_TRUNCADA']) ? $dadosGraf['HORA_TRUNCADA'] : [];
            $datas               = isset($dataGraf['DATA_TRUNCADA']) ? array_unique($dataGraf['DATA_TRUNCADA']) : [];

            $dados["chart"]      = json_encode($reports);
            $dados["horas"]      = json_encode($horas);

            $dados["porDatas"]   = json_encode($byDate);
            $dados["datas"]      = json_encode($datas);
            
            $dados["realizadas"] = $realizadasCount;
            $dados['pie']        = json_encode($cdr->GeraDadosGraficosGrupo($where));
            $dados['callback']   = count($this->CallbackDAO->getAll(" id_status_callback = 1"));

            $dados['selectCallback'] = json_encode($this->StatusCallbackSelect());

            $this
            ->setClassDivContainer("container-fluid")
            ->setBreadcrumb(["Home", "Dashboard"])
            ->render("Home.dashboard", $dados);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GeraDadosGraficos(){
        try{
            $this->CheckSession();

            $type = $this->getQuery('tipo');

            switch($type){
                case 'lastday':
                    $where = " DATE(calldate) = DATE_SUB(CURDATE(), interval 1 day) ";
                break;
                case 'week':
                    $where = " DATE(calldate) >= DATE_SUB(CURDATE(), interval 1 week) ";
                break;
                case 'month':
                    $where = " DATE(calldate) >= DATE_SUB(CURDATE(), interval 1 month) ";
                break;
                default:
                    $where = " DATE(calldate) = CURDATE() ";
                break;
            }

            
            $cdr                 = new \App\Services\CdrService;

            $reports             = [];
            $horas               = [];

            if( in_array($type, ["lastday", "today"]) ){
                $dadosGraf           = $cdr->GeraDadosGraficoHora($where);

                if(!empty($dadosGraf)){
                    $reports = [
                        [
                            "name" => "Atentidas",
                            "data" => $dadosGraf['ANSWERED']['data']
                        ],
                        // [
                        //     "name" => "Ocupadas",
                        //     "data" => $dadosGraf['BUSY']['data']
                        // ],
                        [
                            "name" => "Perdidas",
                            "data" => $dadosGraf['NO_ANSWER']['data']
                        ],
                    ];
                }

                $horas               = isset($dadosGraf['HORA_TRUNCADA']) ? $dadosGraf['HORA_TRUNCADA'] : [];
            }
            

            $whereRealizadas     = " {$where} and src in (SELECT id_sip FROM sip_lanteca.sip)";
            $realizadas          = $cdr->getAll($whereRealizadas, "calldate desc");
            $realizadasCount     = count($realizadas);

            $dados               = $cdr->GetDataDashboard($where);

            $dados["realizadas"] = $realizadasCount;
            $dados['pie']        = $cdr->GeraDadosGraficosGrupo($where);
            $dados['horas']      =  [
                                        "series"     => $reports,
                                        "categories" => $horas
                                    ];

            $this->data = $dados;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function ListaCallback(){
        try{
            $callback = $this->CallbackDAO->ListaCallback();

            $this->data = $callback;
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }
    

    public function AtualizaCallback(){
        try{
            $this->masterMysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $dados = $this->getPut();
            $rows = (new \App\Classes\CallbackClass)->AtualizaCallback($dados);
            
            $this->masterMysqli->commit();

            $this->data = ["rows" => $rows];
            $this->retorna();
        }catch(\Exception $e){
            $this->masterMysqli->rollback();
            throw $e;
        }
    }

    /**
    * Função para verificar quantidade de retornos
    * @author Douglas A. Silva
    * @return array
    */
    public function VerificaCallback(){
        try{
            $qtd = $this->CallbackDAO->getAll(" id_status_callback = 1");


            $this->data = ["nmro_retorno" => count($qtd)];
            $this->retorna();
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>