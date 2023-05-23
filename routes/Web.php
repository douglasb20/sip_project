<?php

use App\Services\CdrService;
use Core\Router;

// Router::get("/","HomeController@index", false);
Router::get("/", "HomeController@Index")->name("home");
Router::get("/login", "AuthController@Index")->name("login");
Router::get("/logout", "AuthController@AuthLogout")->name("logout");
Router::get("/esqueci_minha_senha", "AuthController@Index")->name("esqueci-senha");
Router::get("/call_reports", "CallReportsController@Index")->name("call-reports");

Router::group("/system", function(){

    Router::get("/users", "UsersController@Index")->name("users");
    Router::get("/operators", "SipController@Index")->name("sip");

});

Router::get("/teste", function(){
    
});



?>