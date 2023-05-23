<?php
use Core\Router;
Router::group("/api", function(){

    Router::post('/auth',"AuthController@Authentication");
    Router::post("/login_auth_request", "AuthController@AuthLogin")->name("login-auth");
    Router::post("/validate_login", "AuthController@ValidateLogin")->name("validate-auth");
    Router::get("/get_dados_grafico_dashboard/{tipo:[a-z]}", "HomeController@GeraDadosGraficos")->name("dados-dashboard");
    Router::get("/get_lista_callback", "HomeController@ListaCallback")->name("lista-callback");
    Router::get("/add_callback/{cpf}/{numero}", "Controller@AddCallback")->name("add-callback");

    Router::post("/calls_report", "CallReportsController@CallReports")->name("lista-ligacoes");
    
    Router::put("/atualiza_status_callback", "HomeController@AtualizaCallback")->name("atualiza-callback");


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
            
        });
    });


});


?>