<?php

namespace App\Controller;

class HomeController extends Controller{
    
    public function Index(){
        try {
            $this->CheckSession();
            $cdr = (new \App\Services\CdrService);
            $dadosGraf = $cdr->GeraDadosGrafico();
            $reports = [];
            if(!empty($dadosGraf)){
                $reports = [
                    [
                        "name" => "Perdidas",
                        "data" => $dadosGraf['NO ANSWER']['data']
                    ],
                    [
                        "name" => "Congestão",
                        "data" => $dadosGraf['CONGESTION']['data']
                    ],
                    [
                        "name" => "Atentidas",
                        "data" => $dadosGraf['ANSWERED']['data']
                    ],
                ];
            }

            $horas = array_column($cdr->AgrupaHoraFormatado(), "hora_truncada");

            $dados["chart"] = json_encode($reports);
            $dados["horas"] = json_encode($horas);

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