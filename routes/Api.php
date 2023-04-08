<?php
use Core\Router;
Router::group("/api", function(){
    Router::post('/auth',"AuthController@Authentication", false);
    Router::post("/login_auth_request", "AuthController@AuthLogin")->name("login-auth");
    Router::post("/validate_login", "AuthController@ValidateLogin")->name("validate-auth");
    Router::get("/get_dados_grafico_dashboard/{tipo:[a-z]}", "HomeController@GeraDadosGraficos")->name("dados-dashboard");
    Router::get("/get_lista_callback", "HomeController@ListaCallback")->name("lista-callback");
});


?>