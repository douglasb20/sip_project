<?php
use Core\Router;

// Router::get("/","HomeController@index", false);
Router::get("/", "Controller@index")->name("home");
Router::get("/login", "AuthController@Index");

Router::get("/teste/{id}", "Controller@testes");



?>