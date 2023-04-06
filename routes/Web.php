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
    $re = '/(\d+)@/m';
    $str = 'Local/1101@from-queue/n,0,Local/1201@from-queue/n,0,Local/1202@from-queue/n,0,Local/1203@from-queue/n,0,Local/1206@from-queue/n,0,Local/1301@from-queue/n,0,Local/1305@from-queue/n,0,Local/1306@from-queue/n,0,Local/1307@from-queue/n,0';

    preg_match_all($re, $str, $matches);
    printar(route()->link('dados-dashboard') );
});



?>