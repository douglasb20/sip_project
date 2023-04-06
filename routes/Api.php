<?php
use Core\Router;
Router::group("/api", function(){
    Router::post('/auth',"AuthController@Authentication", false);
    Router::post("/login_auth_request", "AuthController@AuthLogin")->name("login-auth");
    Router::get("/get_dados_grafico_dashboard/{tipo:[a-z]}", "HomeController@GeraDadosGraficos")->name("dados-dashboard");
});


?>