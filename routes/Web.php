<?php
use Core\Router;

// Router::get("/","HomeController@index", false);
Router::get("/",function(){
    
    render("Home");

}, false);

?>