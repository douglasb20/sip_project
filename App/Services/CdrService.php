<?php

namespace App\Services;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class CdrService extends \Core\Defaults\DefaultModel{
    private $mysqli;
    private $dbname;

    function __construct(){
        $host     = $_ENV['CDRDBHOST'];
        $username = $_ENV['CDRDBUSER'];
        $passwd   = $_ENV['CDRDBPWD'];
        $dbname   = $_ENV['CDRDBNAME'];
        $port     = $_ENV['CDRDBPORT'];
        
        $this->dbname = $dbname;
        $this->tabela = "cdrCerto";
        $this->mysqli = new \mysqli($host,$username,$passwd,$dbname, $port);
        $this->mysqli->set_charset("utf8");

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
                        FROM {$this->dbname}.{$this->tabela}
                        WHERE cdrCerto.calldate BETWEEN '{$hora}:00:00' and '{$hora}:59:59' 
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id_sip FROM sip_lanteca.sip)
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
                        FROM {$this->dbname}.{$this->tabela}
                        WHERE Date({$this->tabela}.calldate)= '{$data}' AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id_sip FROM sip_lanteca.sip)
                        GROUP BY data_truncada, status
                    ";
            return $this->executeQuery($query);
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function AgrupaData(){
        try{
            $query = "  SELECT DATE_FORMAT( calldate, '%Y-%m-%d') AS data_truncada,
                        status, 
                        count(*) registros
                        FROM {$this->dbname}.{$this->tabela}
                        WHERE Date(cdrCerto.calldate) BETWEEN DATE_SUB(CURDATE(), INTERVAL 8 DAY) and CURDATE() 
                        AND status IN ('ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id_sip FROM sip_lanteca.sip)
                        GROUP BY data_truncada, status
                    ";
            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }
    
    public function AgrupaHora($where){
        try{
            $query = "  SELECT 
                        DATE_FORMAT(calldate, '%Y-%m-%d %H:00:00') AS hora_truncada, 
                        status, 
                        count(*) registros
                        FROM {$this->dbname}.{$this->tabela}
                        WHERE {$where}
                        AND status IN ('ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id_sip FROM sip_lanteca.sip)
                        GROUP BY hora_truncada, status
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
                        WHERE Date(cdrCerto.calldate) BETWEEN DATE_SUB(CURDATE(), INTERVAL 8 DAY) and CURDATE() 
                        AND status IN ('BUSY','ANSWERED', 'NO ANSWER')
                        AND src NOT IN (SELECT id_sip FROM sip_lanteca.sip)
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
            
            $dados     = $this->AgrupaHora($where);
            $dadosGraf = [];
            $horas     = [];

            if(count($dados) === 1){
                $dadosGraf = [
                    // "BUSY"       => ["data" => [0]],
                    // "CONGESTION" => ["data" => [0]],
                    "ANSWERED"   => ["data" => [0]],
                    "NO_ANSWER"  => ["data" => [0]],
                    "HORA_TRUNCADA" => []
                ];

                $horas = [date("Y-m-d 06:00:00", strtotime($dados[0]['hora_truncada']))];
            }


            foreach($dados as $key => $dado){

                // if($dado['status'] === "BUSY"){
                //     $dadosGraf["BUSY"]["data"][] = $dado["registros"];
                // }else{
                    
                //     $dadosGraf["BUSY"]["data"][] = 0;
                // }

                // if($dado['status'] === "CONGESTION"){
                //     $dadosGraf["CONGESTION"]["data"][] = $dado["registros"];
                // }else{
                    
                //     $dadosGraf["CONGESTION"]["data"][] = 0;
                // }

                if($dado['status'] === "ANSWERED"){
                    $dadosGraf["ANSWERED"]["data"][] = $dado["registros"];
                }else{
                    
                    $dadosGraf["ANSWERED"]["data"][] = 0;
                }

                if($dado['status'] === "NO ANSWER"){
                    $dadosGraf["NO_ANSWER"]["data"][] = $dado["registros"];
                }else{
                    
                    $dadosGraf["NO_ANSWER"]["data"][] = 0;
                }
                array_push($horas, $dado['hora_truncada']);
            }

            if(!empty($dadosGraf)){
                $dadosGraf['HORA_TRUNCADA'] = array_unique($horas);
            }

            return $dadosGraf;
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function GeraDadosGraficoData(){
        try{
            
            $datas     = $this->AgrupaData();
            $dadosGraf = [];
            $grafico   = [];

            foreach($datas as $key => $dt){
                $dadosGraf[$dt['data_truncada']][$dt['status']]["data"] = $dt['registros'];
            }

            foreach($dadosGraf as $key => $graf){
                
                // $grafico["BUSY"]["data"][]       = isset($graf['BUSY'])         ? $graf['BUSY']["data"]         : 0.05;
                // $grafico["CONGESTION"]["data"][] = isset($graf['CONGESTION'])   ? $graf['CONGESTION']["data"]   : 0.05;
                $grafico["ANSWERED"]["data"][]   = isset($graf['ANSWERED'])     ? $graf['ANSWERED']["data"]     : 0.05;
                $grafico["NO ANSWER"]["data"][]  = isset($graf['NO ANSWER'])    ? $graf['NO ANSWER']["data"]    : 0.05;

                $grafico["DATA_TRUNCADA"][]      = date("d/m", strtotime($key . "00:00:00"));

            }

            return ($grafico);
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
                            AND src NOT IN (SELECT id_sip FROM sip_lanteca.sip)
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
            $query      = "SELECT * FROM sip_lanteca.queue where queue_code not in (99,9900)";
            $exts       = $this->executeQuery($query);
            $extensions = [];
            $re         = '/(\d+)@/m';

            foreach($exts as $key => $ext){
                $qry                        = "SELECT member FROM sip_lanteca.queue_members WHERE id_queue = {$ext['id_queue']}";
                $resp                       = $this->executeQuery($qry);
                $extensions[$ext['queue_code']]['nome']   = $ext["queue_name"];
                $extensions[$ext['queue_code']]['ramais'] = array_column($resp,"member");
            }

            // foreach($extensions as $key => $exts){
            //     preg_match_all($re, $exts['ramais'], $matches);
            //     $extensions[$key]['ramais'] = $matches[1];
            // }

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
                $query = "SELECT count(*) as qtd FROM {$this->dbname}.{$this->tabela}
                WHERE {$where}
                    AND src not in (SELECT id_sip FROM sip_lanteca.sip)
                    AND dstchannel in (".implode(",", $queue['ramais']).")";
                
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
            $query = "SELECT id_sip, concat(id_sip, ' - ', callerId) as text  FROM sip_lanteca.sip";
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
                        sec_to_time(cast(c.duration as int)) as time_duration,
                        (SELECT d.callerId FROM sip_lanteca.sip as d WHERE d.id_sip = c.dstchannel) as dst_name,
                        (SELECT d.callerId FROM sip_lanteca.sip as d WHERE d.id_sip = c.src) as src_name
                    FROM
                        asterisk.{$this->tabela} as c
                    WHERE
                        {$where}";

            return $this->executeQuery($query);
            
        }catch(\Exception $e){
            throw $e;
        }
    }

}

?>