<?php
use Core\Router;

Router::group("/api", function(){

    Router::post('/auth',"AuthController@Authentication");
    Router::post("/login_auth_request", "AuthController@AuthLogin")->name("login-auth");
    Router::get("/password_forgot_request/{user_email:[\W|\w]}", "AuthController@ForgotPassword")->name("forgot-password");
    Router::post("/request_recover/{id_user}", "AuthController@RequestRecover")->name("request-recover");
    Router::get("/get_dados_grafico_dashboard/{tipo:[a-z]}", "HomeController@GeraDadosGraficos")->name("dados-dashboard");
    Router::get("/get_lista_callback", "HomeController@ListaCallback")->name("lista-callback");
    Router::get("/add_callback/{cpf}/{numero}/{id_empresa}", "Controller@AddCallback")->name("add-callback");

    Router::post("/calls_report", "CallReportsController@CallReports")->name("lista-ligacoes");
    
    Router::put("/atualiza_status_callback", "HomeController@AtualizaCallback")->name("atualiza-callback");

    Router::get("/check_callback", "HomeController@VerificaCallback")->name("verifica-callback");

    Router::group("/system",function(){

        Router::group("/users", function(){
            Router::post("/get_users", "UsersController@GetListUsers")->name("users-list");
            Router::get("/get_user/{id}", "UsersController@GetUser")->name("get-user");
            Router::post("/update_user/{id}", "UsersController@UpdateUser")->name("update-user");
            Router::post("/new_user", "UsersController@NewUser")->name("new-user");
            Router::get("/get_user_permissions/{id}", "UsersController@GetUserPermissions")->name("user-permissions");
            Router::post("/save_user_permissions/{id}", "UsersController@SaveUserPermissions")->name("save-permissions");
            Router::get("/change_user_status/{id}/{status}", "UsersController@ToggleUserStatus")->name("change-user-status");
        });

        Router::group("/sip", function(){
            Router::post("/get_sips", "SipController@GetSipList")->name("sip-list");
            Router::get("/update_sip_config", "SipController@UpdateSipsFromConfig")->name("get-sip-config");
            Router::post("/save_sip", "SipController@SaveSip")->name("save-sip");
            Router::get("/change_sip_status/{id_sip}/{sip_status}", "SipController@ToggleSipStatus")->name("change-sip-status");
        });
    });


});


?>