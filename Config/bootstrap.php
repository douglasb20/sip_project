<?php

global $_DB_MYSQLI;

$envFile = ".env";


$dotenv = Dotenv\Dotenv::createImmutable( realpath( __DIR__ . '/../'),$envFile);
$dotenv->load();

$_DB_MYSQLI = (new Db)->getMysqli();

?>