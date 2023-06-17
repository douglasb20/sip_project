<?php

use App\Services\CdrService;
use Core\Router;

// Router::get("/","HomeController@index", false);
Router::get("/", "HomeController@Index")->name("home");
Router::get("/login", "AuthController@Index")->name("login");
Router::get("/recover_password/{forgot_token:[\W|\w]}", "AuthController@RecoverPassword")->name("recover-password");
Router::get("/logout", "AuthController@AuthLogout")->name("logout");
Router::get("/esqueci_minha_senha", "AuthController@Index")->name("esqueci-senha");
Router::get("/call_reports", "CallReportsController@Index")->name("call-reports");

Router::group("/pabx", function(){
    Router::get("/call_panel", "CallsPanelController@Index")->name("calls-panel");
});

Router::group("/system", function(){

    Router::get("/users", "UsersController@Index")->name("users");
    Router::get("/operators", "SipController@Index")->name("sip");

});

Router::get("/teste", function(){
    $cdr = new CdrService;
    $arquivo = ROOT_PATH . '/queues-base.conf';
    $config  = (new  \App\Classes\ConfiLoaderClass)->loadConfig($arquivo);
    printar($config);
});



?>