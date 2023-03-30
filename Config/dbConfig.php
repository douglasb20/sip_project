<?php

// Reporta Exceptions do mysql
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class Db{

    private $mysqli;

    function __construct(){
        try{
            $host     = $_ENV['DBHOST'];
            $username = $_ENV['DBUSER'];
            $passwd   = $_ENV['DBPWD'];
            $dbname   = $_ENV['DBNAME'];
            $port     = $_ENV['DBPORT'];

            $this->mysqli = new mysqli($host,$username,$passwd,$dbname, $port);
            $this->mysqli->set_charset("utf8");

            $GLOBALS['_DB_MYSQLI'] = $this->mysqli;
            
        }catch(Exception $e){
            throw $e;
        }
    }

    public function getMysqli(){
        try{
            return $this->mysqli;
        }catch(Exception $e){
            throw $e;
        }
    }
}