<?php

namespace App\Classes;

class CdrClass extends \Core\Defaults\DefaultClassController{
    
    public function CallReports($data){
        try{
            extract($data);

            $where = "1=1";

            if( is_array( $origem ) ){
                $origem = implode(",",$origem);
                $where .= " AND src in ({$origem})";
            }else{
                if($origem !== "-1"){
                    $where .= " AND src = {$origem}";
                }
            }

            if( is_array( $destino ) ){
                $destino = implode(",",$destino);
                $where .= " AND dst in ({$destino})";
            }else{
                if($destino !== "-1"){
                    $where .= " AND dst = {$destino}";
                }
            }

            if( is_array( $status ) ){
                $status = implode("','",$status);
                $where .= " AND status in ('{$status}')";
            }else{
                if($status !== "-1"){
                    $where .= " AND status = '{$status}'";
                }
            }

            $data_de  = \DateTime::createFromFormat('d/m/Y', $data_de)->format('Y-m-d');
            $data_ate = \DateTime::createFromFormat('d/m/Y', $data_ate)->format('Y-m-d');

            $where .= " AND DATE(calldate) BETWEEN '$data_de' AND '$data_ate' ";

            $cdr = (new \App\Services\CdrService)->CallReports($where);

            return $cdr;
        }catch(\Exception $e){
            throw $e;
        }
    }
}

?>