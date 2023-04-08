<?php
define("ROOT_PATH", realpath(__DIR__ . "/../"));

define("CONTROLLER_NAMESPACE", "App\\Controller\\");
define("CONTROLLER_PATH", "App/Controller/");

define("MODEL_NAMESPACE", "App\\Model\\");
define("MODEL_PATH", "App/Model/");

define("VIEW_PATH", ROOT_PATH . "/App/View/");

if(isset($_SERVER['REQUEST_SCHEME'])){
    define("URL_ROOT", ($_SERVER['REQUEST_SCHEME'] === "https" || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on") ? "https://"  :  "http://") . $_SERVER['HTTP_HOST'] .'/'. trim($_ENV['BASE_URL'],"/") );
    define("URL_IMAGES", URL_ROOT . "assets/images");
}

?>