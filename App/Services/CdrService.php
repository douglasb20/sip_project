<?php

namespace App\Services;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class CdrService extends \Core\Defaults\DefaultModel{
    private $mysqli;

    function __construct(){
        $host     = $_ENV['CDRDBHOST'];
        $username = $_ENV['CDRDBUSER'];
        $passwd   = $_ENV['CDRDBPWD'];
        $dbname   = $_ENV['CDRDBNAME'];
        $port     = $_ENV['CDRDBPORT'];

        $this->mysqli = new \mysqli($host,$username,$passwd,$dbname, $port);
        $this->mysqli->set_charset("utf8");
        $this->tabela = "cdrCerto";

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
                        WHERE cdrCerto.calldate BETWEEN '{$hora}:00:00' and '{$hora}:59:59' 
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id FROM asterisk.devices)
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
                        AND src NOT IN (SELECT id FROM asterisk.devices)
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
                        WHERE Date(cdrCerto.calldate) BETWEEN DATE_SUB(CURDATE(), INTERVAL 8 DAY) and CURDATE() 
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id FROM asterisk.devices)
                        GROUP BY data_truncada
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }
    
    public function AgrupaHora($where){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d %H') AS hora_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE {$where}
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id FROM asterisk.devices)
                        GROUP BY hora_truncada
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }
    
    public function AgrupaHoraFormatado($where = " DATE(calldate) = CURDATE() "){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d %H:00:00') AS hora_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE {$where} 
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id FROM asterisk.devices)
                        GROUP BY hora_truncada
                    ";
            $resp = $this->executeQuery($query);
            if(count($resp) == 1){
                array_unshift($resp, ["hora_truncada" => date("Y-m-d 06:00:00.000")]);
            }

            return $resp;
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AgrupaDataFormatado(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%d/%m') AS data_truncada
                        FROM asteriskcdrdb.cdrCerto
                        WHERE Date(cdrCerto.calldate) BETWEEN '".date("Y-m-d", strtotime("-8 days"))."' and '".date("Y-m-d")."' 
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id FROM asterisk.devices)
                        GROUP BY data_truncada
                        order by calldate
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GeraDadosGraficoHora($where = " DATE(calldate) = CURDATE() "){
        try{
            
            $dados = $this->AgrupaHora($where);
            $horas = array_column($dados, "hora_truncada");
            $dadosGraf = [];

            if(count($horas) === 1){
                $dadosGraf = [
                    "BUSY"       => ["data" => [0]],
                    "CONGESTION" => ["data" => [0]],
                    "ANSWERED"   => ["data" => [0]],
                    "NO_ANSWER"  => ["data" => [0]],
                ];
            }

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
                    $dadosGraf["NO_ANSWER"]["data"][] = $novoDado[$index]["registros"];
                }else{
                    
                    $dadosGraf["NO_ANSWER"]["data"][] = 0;
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

    public function GetDataDashboard($where){
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
                            AND {$where}
                            AND src NOT IN (SELECT id FROM asterisk.devices)
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

    public function GetLinkedQueue(){
        try{
            $query      = "SELECT extension, descr FROM asterisk.queues_config where extension != 99";
            $exts       = $this->executeQuery($query);
            $extensions = [];
            $re         = '/(\d+)@/m';

            foreach(array_column($exts, "extension") as $key => $ext){
                $qry                        = "SELECT group_concat(data) as linked FROM asterisk.queues_details WHERE keyword = 'member' AND id = {$ext}";
                $resp                       = $this->executeQuery($qry)[0]['linked'];
                $extensions[$ext]['nome']   = $exts[$key]["descr"];
                $extensions[$ext]['ramais'] = $resp;
            }

            foreach($extensions as $key => $exts){
                preg_match_all($re, $exts['ramais'], $matches);
                $extensions[$key]['ramais'] = $matches[1];
            }

            return $extensions;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GeraDadosGraficosGrupo($where){
        try{
            $queues = $this->GetLinkedQueue();
            $data = [];

            foreach($queues as $key => $queue){
                $result = ["name" => $queue['nome']];
                $query = "SELECT count(*) as qtd FROM cdrCerto
                WHERE {$where}
                    AND src not in (SELECT id FROM asterisk.devices)
                    AND dst in (".implode(",", $queue['ramais']).")";
                
                $registo = $this->executeQuery($query)[0]['qtd'];
                $result["value"] = (int)$registo;
                $data[] = $result;
            }
            

            return $data;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GetDevices(){
        try{
            $query = "SELECT id, concat(id, ' - ', description) as text  FROM asterisk.devices";
            $devices = $this->executeQuery($query);

            return $devices;
            
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function CallReports($where){
        try{
            $query = "SELECT 
                        c.*,
                        sec_to_time(c.tempo) as time_duration,
                        (SELECT d.description FROM asterisk.devices as d WHERE d.id = c.dst) as dst_name,
                        (SELECT d.description FROM asterisk.devices as d WHERE d.id = c.src) as src_name
                    FROM
                        asteriskcdrdb.cdrCerto as c
                    WHERE
                        {$where}";

            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>