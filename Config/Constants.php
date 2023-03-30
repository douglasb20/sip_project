<?php
define("ROOT_PATH", realpath(__DIR__ . "/../"));

define("CONTROLLER_NAMESPACE", "App\\Controller\\");
define("CONTROLLER_PATH", "App/Controller/");

define("MODEL_NAMESPACE", "App\\Model\\");
define("MODEL_PATH", "App/Model/");


if(isset($_SERVER['REQUEST_SCHEME'])){
    define("URL_ROOT", ($_SERVER['REQUEST_SCHEME'] === "http" || $_SERVER['HTTPS'] === "on" ? "http://"  :  "http://") . $_SERVER['HTTP_HOST'] .'/'. trim($_ENV['BASE_URL'],"/") );
    define("URL_IMAGES", URL_ROOT . "assets/images");
}

?>