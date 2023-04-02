<?php

namespace App\Services;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class CdrService extends \Core\Defaults\DefaultModel{
    private $mysqli;
    private $data;

    function __construct(){
        $host     = $_ENV['CDRDBHOST'];
        $username = $_ENV['CDRDBUSER'];
        $passwd   = $_ENV['CDRDBPWD'];
        $dbname   = $_ENV['CDRDBNAME'];
        $port     = $_ENV['CDRDBPORT'];

        $this->mysqli = new \mysqli($host,$username,$passwd,$dbname, $port);
        $this->mysqli->set_charset("utf8");
        $this->tabela = "cdrCerto";
        $this->data = date("Y-03-31");

        parent::__construct($this->mysqli);
    }

    function __destruct(){
        try{
            if($this->mysqli != null){
                mysqli_close($this->mysqli);
            }
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GraficoHora(string $hora){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%H:00') AS hora_truncada, COUNT(*) AS registros, status
                        FROM asteriskcdrdb.cdrCerto
                        WHERE cdrCerto.calldate BETWEEN '{$this->data} {$hora}:00:00' and '{$this->data} {$hora}:59:59' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        GROUP BY hora_truncada, status
                    ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GraficoData(string $data){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d') AS data_truncada, COUNT(*) AS registros, status
                        FROM asteriskcdrdb.cdrCerto
                        WHERE Date(cdrCerto.calldate)= '{$data}' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        GROUP BY data_truncada, status
                    ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AgrupaData(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d') AS data_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE Date(cdrCerto.calldate) BETWEEN '".date("Y-m-d", strtotime("-7 days"))." ' and '".date("Y-m-d")."' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        GROUP BY data_truncada
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }
    
    public function AgrupaHora(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%H') AS hora_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE cdrCerto.calldate BETWEEN '{$this->data} 00:00:00' and '{$this->data} 23:59:59' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        GROUP BY hora_truncada
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }
    
    public function AgrupaHoraFormatado(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%dT%H:00:00.000Z') AS hora_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE cdrCerto.calldate BETWEEN '{$this->data} 00:00:00' and '{$this->data} 23:59:59' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        GROUP BY hora_truncada
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AgrupaDataFormatado(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%d/%m') AS data_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE Date(cdrCerto.calldate) BETWEEN '".date("Y-m-d", strtotime("-7 days"))."' and '".date("Y-m-d")."' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        GROUP BY data_truncada
                        order by calldate
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GeraDadosGraficoHora(){
        try{
            
            $dados = $this->AgrupaHora();
            $horas = array_column($dados, "hora_truncada");
            $dadosGraf = [];
            foreach($horas as $key => $hr){
                $novoDado = $this->GraficoHora($hr);

                if(in_array("BUSY",array_column($novoDado, "status"))){
                    $index = array_search("BUSY",array_column($novoDado, "status"));
                    $dadosGraf["BUSY"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["BUSY"]["data"][] = 0;
                }

                if(in_array("CONGESTION",array_column($novoDado, "status"))){
                    $index = array_search("CONGESTION",array_column($novoDado, "status"));
                    $dadosGraf["CONGESTION"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["CONGESTION"]["data"][] = 0;
                }

                if(in_array("ANSWERED",array_column($novoDado, "status"))){
                    $index = array_search("ANSWERED",array_column($novoDado, "status"));
                    $dadosGraf["ANSWERED"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["ANSWERED"]["data"][] = 0;
                }

                if(in_array("NO ANSWER",array_column($novoDado, "status"))){
                    $index = array_search("NO ANSWER",array_column($novoDado, "status"));
                    $dadosGraf["NO ANSWER"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["NO ANSWER"]["data"][] = 0;
                }
            }
            return $dadosGraf;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GeraDadosGraficoData(){
        try{
            
            $dados = $this->AgrupaData();
            $datas = array_column($dados, "data_truncada");
            $dadosGraf = [];
            foreach($datas as $key => $dt){
                $novoDado = $this->GraficoData($dt);

                if(in_array("BUSY",array_column($novoDado, "status"))){
                    $index = array_search("BUSY",array_column($novoDado, "status"));
                    $dadosGraf["BUSY"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["BUSY"]["data"][] = 0;
                }

                if(in_array("CONGESTION",array_column($novoDado, "status"))){
                    $index = array_search("CONGESTION",array_column($novoDado, "status"));
                    $dadosGraf["CONGESTION"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["CONGESTION"]["data"][] = 0;
                }

                if(in_array("ANSWERED",array_column($novoDado, "status"))){
                    $index = array_search("ANSWERED",array_column($novoDado, "status"));
                    $dadosGraf["ANSWERED"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["ANSWERED"]["data"][] = 0;
                }

                if(in_array("NO ANSWER",array_column($novoDado, "status"))){
                    $index = array_search("NO ANSWER",array_column($novoDado, "status"));
                    $dadosGraf["NO ANSWER"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["NO ANSWER"]["data"][] = 0;
                }
            }
            return $dadosGraf;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GetDataDashboard(){
        try{
            $query = "  SELECT count(cdr_formatado.status) as qtd, subquery.status from 
                        (SELECT 'BUSY' AS status UNION ALL SELECT 'ANSWERED' AS status UNION ALL SELECT 'NO ANSWER' AS status) AS subquery
                        left join 
                        (
                        SELECT 
                            *
                        FROM
                            cdrCerto 
                        WHERE
                            1=1
                            AND date(calldate) = '{$this->data}'
                        order by cdrCerto.calldate desc) as cdr_formatado
                        on cdr_formatado.status = subquery.status
                        group by subquery.status";
            $status = $this->executeQuery($query);
            $dados = [];

            foreach($status as $v){
                $dados[str_replace(" ","_",strtolower($v['status']))] = $v['qtd'];
            }

            return $dados;
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>