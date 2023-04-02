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
        $this->tabela = "cdr";
        $this->data = date("Y-m-d");

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
            $query = "  SELECT DATE_FORMAT( calldate, '%H:00') AS hora_truncada, COUNT(*) AS registros, disposition
                        FROM asteriskcdrdb.cdr
                        WHERE cdr.calldate BETWEEN '{$this->data} {$hora}:00:00' and '{$this->data} {$hora}:59:59' AND disposition IN ('BUSY','ANSWERED', 'NO ANSWER', 'CONGESTION')
                        GROUP BY hora_truncada, disposition
                    ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GraficoData(string $data){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d') AS data_truncada, COUNT(*) AS registros, disposition
                        FROM asteriskcdrdb.cdr
                        WHERE cdr.calldate BETWEEN '{$data} 00:00:00' and '{$data} 23:59:59' AND disposition IN ('BUSY','ANSWERED', 'NO ANSWER', 'CONGESTION')
                        AND lastapp in ('Dial' , 'Busy') AND dstchannel != ''
                        GROUP BY data_truncada, disposition
                    ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AgrupaData(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d') AS data_truncada
                        FROM asteriskcdrdb.cdr
                        WHERE cdr.calldate BETWEEN '".date("Y-m-d", strtotime("-7 days"))." 00:00:00' and '".date("Y-m-d")." 23:59:59' AND disposition IN ('BUSY','ANSWERED', 'NO ANSWER', 'CONGESTION')
                        AND lastapp in ('Dial' , 'Busy') AND dstchannel != ''
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
                        FROM asteriskcdrdb.cdr
                        WHERE cdr.calldate BETWEEN '{$this->data} 00:00:00' and '{$this->data} 23:59:59' AND disposition IN ('BUSY','ANSWERED', 'NO ANSWER', 'CONGESTION')
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
                        FROM asteriskcdrdb.cdr
                        WHERE cdr.calldate BETWEEN '{$this->data} 00:00:00' and '{$this->data} 23:59:59' AND disposition IN ('BUSY','ANSWERED', 'NO ANSWER', 'CONGESTION')
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
                        FROM asteriskcdrdb.cdr
                        WHERE cdr.calldate BETWEEN '".date("Y-m-d", strtotime("-7 days"))." 00:00:00' and '".date("Y-m-d")." 23:59:59' AND disposition IN ('BUSY','ANSWERED', 'NO ANSWER', 'CONGESTION')
                        AND lastapp in ('Dial' , 'Busy') AND dstchannel != ''
                        GROUP BY data_truncada
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

                if(in_array("BUSY",array_column($novoDado, "disposition"))){
                    $index = array_search("BUSY",array_column($novoDado, "disposition"));
                    $dadosGraf["BUSY"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["BUSY"]["data"][] = 0;
                }

                if(in_array("CONGESTION",array_column($novoDado, "disposition"))){
                    $index = array_search("CONGESTION",array_column($novoDado, "disposition"));
                    $dadosGraf["CONGESTION"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["CONGESTION"]["data"][] = 0;
                }

                if(in_array("ANSWERED",array_column($novoDado, "disposition"))){
                    $index = array_search("ANSWERED",array_column($novoDado, "disposition"));
                    $dadosGraf["ANSWERED"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["ANSWERED"]["data"][] = 0;
                }

                if(in_array("NO ANSWER",array_column($novoDado, "disposition"))){
                    $index = array_search("NO ANSWER",array_column($novoDado, "disposition"));
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

                if(in_array("BUSY",array_column($novoDado, "disposition"))){
                    $index = array_search("BUSY",array_column($novoDado, "disposition"));
                    $dadosGraf["BUSY"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["BUSY"]["data"][] = 0;
                }

                if(in_array("CONGESTION",array_column($novoDado, "disposition"))){
                    $index = array_search("CONGESTION",array_column($novoDado, "disposition"));
                    $dadosGraf["CONGESTION"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["CONGESTION"]["data"][] = 0;
                }

                if(in_array("ANSWERED",array_column($novoDado, "disposition"))){
                    $index = array_search("ANSWERED",array_column($novoDado, "disposition"));
                    $dadosGraf["ANSWERED"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["ANSWERED"]["data"][] = 0;
                }

                if(in_array("NO ANSWER",array_column($novoDado, "disposition"))){
                    $index = array_search("NO ANSWER",array_column($novoDado, "disposition"));
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
            $query = "  SELECT 
                        COUNT(cdr.disposition) AS quantidade,
                            subquery.disposition
                        FROM 
                            (SELECT 'BUSY' as disposition UNION ALL
                            SELECT 'ANSWERED' as disposition UNION ALL
                            SELECT 'CONGESTION' as disposition UNION ALL
                            SELECT 'NO ANSWER' as disposition) as subquery
                        LEFT JOIN 
                            cdr ON subquery.disposition = cdr.disposition 
                        AND DATE(cdr.calldate) = '2023-03-29' 
                        AND lastapp in ('Dial' , 'Busy') AND dstchannel != ''
                        GROUP BY 
                            subquery.disposition";
            $status = $this->executeQuery($query);
            $dados = [];

            foreach($status as $v){
                $dados[str_replace(" ","_",strtolower($v['disposition']))] = $v['quantidade'];
            }

            return $dados;
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>