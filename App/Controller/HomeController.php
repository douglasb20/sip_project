<?php

namespace App\Controller;

class HomeController extends Controller{



    public function Index(){
        try {
            $this->CheckSession();
            $cdr             = (new \App\Services\CdrService);
            $dadosGraf       = $cdr->GeraDadosGraficoHora();
            $dataGraf        = $cdr->GeraDadosGraficoData();

            $where           = " DATE(calldate) = CURDATE() ";
            $whereRealizadas = " {$where} and src in (1101,1201,1202,1203,1206,1301,1305,1306,1307,1309,1402,1501,1701,1702,9999,90001)";
            $realizadas      = $cdr->getAll($whereRealizadas, "calldate desc");
            $realizadasCount = count($realizadas);
            $reports         = [];
            $byDate          = [];

            if(!empty($dadosGraf)){
                $reports = [
                    
                    // [
                    //     "name" => "Recebidas",
                    //     "data" => $dadosGraf['CONGESTION']['data']
                    // ],
                    [
                        "name" => "Atentidas",
                        "data" => $dadosGraf['ANSWERED']['data']
                    ],
                    [
                        "name" => "Ocupadas",
                        "data" => $dadosGraf['BUSY']['data']
                    ],
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
                    // [
                    //     "name" => "Recebidas",
                    //     "data" => $dataGraf['CONGESTION']['data']
                    // ],
                    [
                        "name" => "Atentidas",
                        "data" => $dataGraf['ANSWERED']['data']
                    ]
                ];
            }

            $dados               = $cdr->GetDataDashboard($where);

            $horas               = array_column($cdr->AgrupaHoraFormatado(), "hora_truncada");
            $datas               = array_column($cdr->AgrupaDataFormatado(), "data_truncada");

            $dados["chart"]      = json_encode($reports);
            $dados["horas"]      = json_encode($horas);

            $dados["porDatas"]   = json_encode($byDate);
            $dados["datas"]      = json_encode($datas);
            
            $dados["realizadas"] = $realizadasCount;
            $dados['pie']        = json_encode($cdr->GeraDadosGraficosGrupo($where));
            $dados['callback']   = count($this->CallbackDAO->getAll(" id_status_callback = 1"));

            $this
            ->setClassDivContainer("container-fluid")
            // ->setTituloPagina("Dashboard")
            ->setBreadcrumb(["Home", "Dashboard"])
            ->setShowMenu(true)
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
                        [
                            "name" => "Ocupadas",
                            "data" => $dadosGraf['BUSY']['data']
                        ],
                        [
                            "name" => "Perdidas",
                            "data" => $dadosGraf['NO_ANSWER']['data']
                        ],
                    ];
                }

                $horas = array_column($cdr->AgrupaHoraFormatado($where), "hora_truncada");
            }
            

            $whereRealizadas     = " {$where} and src in (1101,1201,1202,1203,1206,1301,1305,1306,1307,1309,1402,1501,1701,1702,9999,90001)";
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
}

?>