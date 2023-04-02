<?php

namespace App\Controller;

class HomeController extends Controller{
    
    public function Index(){
        try {
            $this->CheckSession();
            $cdr = (new \App\Services\CdrService);
            $dadosGraf = $cdr->GeraDadosGraficoHora();
            $dataGraf = $cdr->GeraDadosGraficoData();

            $whereRealizadas = " DATE(calldate) = '2023-03-31' and src in (1101,1201,1202,1203,1206,1301,1305,1306,1307,1309,1402,1501,1701,1702,9999,90001)";
            $realizadas = $cdr->getAll($whereRealizadas, "calldate desc");
            $realizadasCount = count($realizadas);
            $reports = [];
            $byDate = [];
            if(!empty($dadosGraf)){
                $reports = [
                    [
                        "name" => "Perdidas",
                        "data" => $dadosGraf['NO ANSWER']['data']
                    ],
                    [
                        "name" => "Recebidas",
                        "data" => $dadosGraf['CONGESTION']['data']
                    ],
                    [
                        "name" => "Atentidas",
                        "data" => $dadosGraf['ANSWERED']['data']
                    ],
                    [
                        "name" => "Ocupadas",
                        "data" => $dadosGraf['BUSY']['data']
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

            $dados = $cdr->GetDataDashboard();

            $horas = array_column($cdr->AgrupaHoraFormatado(), "hora_truncada");
            $datas = array_column($cdr->AgrupaDataFormatado(), "data_truncada");

            $dados["chart"] = json_encode($reports);
            $dados["horas"] = json_encode($horas);

            $dados["porDatas"] = json_encode($byDate);
            $dados["datas"] = json_encode($datas);
            
            $dados["realizadas"] = $realizadasCount;

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
}

?>