<?php

use App\Services\CdrService;
use Core\Router;

// Router::get("/","HomeController@index", false);
Router::get("/", "HomeController@Index")->name("home");
Router::get("/login", "AuthController@Index")->name("login");
Router::get("/logout", "AuthController@AuthLogout")->name("logout");
Router::get("/esqueci_minha_senha", "AuthController@Index")->name("esqueci-senha");

Router::get("/teste", function(){
    $cdr = new CdrService;
    printar($cdr->AgrupaDataFormatado());
});



?>