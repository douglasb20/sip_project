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

Router::group("/call_panel", function(){
    Router::get("/", "CallsPanelController@Index")->name("calls-panel");
});

Router::group("/system", function(){

    Router::get("/users", "UsersController@Index")->name("users");
    Router::get("/operators", "SipController@Index")->name("sip");

});

Router::get("/teste", function(){
    $forgot = [
        "id"      => 2,
        "expires" => date("Y-m-d H:i:s", strtotime("+ 3 days"))
    ];

    $url_token = encrypt(json_encode($forgot));

    echo URL_ROOT . route()->link("recover-password") .$url_token;
    printar(decrypt("VEFFOUM3RDRhM1NnRXZLZklzZU5mZEhZbTBQdmlGN1NTYUJtNjFOWml5c0k1OTkrWG5lQlprRFRzQVJvd1B2Yg"));
});



?>