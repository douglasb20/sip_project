<?php

use App\Services\CdrService;
use App\Services\TwigService;
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
    Router::get("/profile", "UsersController@ProfileIndex")->name("profile");
    Router::get("/operators", "SipController@Index")->name("sip");
    Router::get("/group_permission", "GroupPermissionController@Index")->name("group-permission");

});

Router::get("/teste", function(){
    printar((new \App\Classes\PhpSysInfoClass)->getCpuLoadPercentage());
});



?>