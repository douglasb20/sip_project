<?php

namespace Core;

use Exception;

class Router{

    private static array $routes = [
        "get"    => [],
        "post"   => [],
        "put"    => [],
        "delete" => [],
    ];
    
    private static string $subfolder            = "/";
    private static array $group                 = [];
    private static array $types                 = ['get','post', 'put', 'delete'];
    private static array $lastRouteAdded        = [];
    private static array $executeBefore         = [];
    private static null | bool $validateByGroup = false;
    private static null | bool $groupValidate = null;
    private static string $direction = "";

    public static function baseUrl($subfolder){
        self::$subfolder = $subfolder;
    }

    public static function getBaseUrl(){
        return self::$subfolder;
    }

    public static function getRouteList(bool $all = false ,bool $print= false): mixed{
        self::initiateRoutes();
        $routes = self::$routes;

        if($all){
            $routes = [...$routes['get'], ...$routes['post'],...$routes['put'], ...$routes['delete']];
        }

        if(!$print){
            return $routes;
        }

        printar($routes);

    }

    private static function pull($type, $uri, string|callable $action){
        try{
            self::$lastRouteAdded = [];
            $getFileInfo          = debug_backtrace()[1];
            $getFileInfo          = substr($getFileInfo['file'],-7)  ;
            $fileCalled           = str_replace(".php","",$getFileInfo);

            $sub                  = !empty(self::$group)  ? "/" . implode("/", self::$group) : "";
            
            $uri                  = strlen( $uri ) > 1 && $uri[0] === "/" ? substr($uri, 1) : $uri;
            $uri                  = trim(self::$subfolder,"/"). $sub ."/". ($uri === "/" ? "" : $uri);
            $uri                  = (trim(self::$subfolder,"/")."/") === $uri ? $uri : trim($uri,"/");

            $arr_uri              = explode("/", $uri);
            $array_regex          = [];
            $hasOpcional          = false;
            $removeStrUri         = "";

            if(substr_count($uri, "?}") > 1){
                throw new Exception("Variavel opcional, não pode haver mais de 1",-1);
            }else if( substr_count($uri, "?}") === 1 ){
                $separaUri = end($arr_uri);

                if(substr_count($separaUri, "?}") !== 1){
                    throw new Exception("Variavel opcional tem que ser a ultima atribuida.",-1);
                }

                $hasOpcional  = true;
                $removeStrUri = $separaUri;
            }

            foreach($arr_uri as $key => $val){
                $pattern = '/\{([\w\:\W]+?)\??\}/i';

                if(preg_match($pattern,$val)){
                    if(str_contains($val,":" )){
                        $regex_uri = explode(":", $val);
                        $regex_uri = $regex_uri[1];
                        $regex_uri = preg_replace("/[+]/i","", $regex_uri);

                        $array_regex[] = trim($regex_uri, "{}") . "+";
                    }else{
                        $array_regex[] = '[a-z0-9]+';
                    }
                    
                }else{
                    $array_regex[] = $val;
                }
            }
            
            $novo = [
                "id"       => uniqid("", true),
                "uri"      => str_replace("?","",$uri),
                "action"   => $action,
                "regex"    => join("\/", $array_regex),
                "type"     => self::$direction,
                "alias"    => "",
                "method"   => $type
            ];

            self::$routes[$type][]  = $novo;
            self::$lastRouteAdded[] = $novo;

            if($hasOpcional){
                array_pop($array_regex);
                
                $newUri = str_replace("/".$removeStrUri ,"",$uri);
                
                $others = [
                    "id"    => uniqid("", true),
                    "uri"   => str_contains( $newUri, "/") ? $newUri : $newUri . "/",
                    "regex" => join("\/", $array_regex)
                ];

                $add = [...$novo, ...$others];
                self::$routes[$type][]  = $add;
                self::$lastRouteAdded[] = $add;
            }

        }catch(Exception $e){
            throw $e;
        }
    }

    public static function match(array $types, string $uri, string|callable $action=null){
        try{
            foreach($types as $type){
                self::pull(strtolower($type), $uri, $action);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function get(string $uri=null, string|callable $action=null){
        try{
            self::pull('get', $uri, $action);
            return new self;
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function post(string $uri=null, string|callable $action=null){
        try{
            self::pull('post', $uri, $action);
            
            return new self;
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function put(string $uri=null, string|callable $action=null){
        try{
            self::pull('put', $uri, $action);
            return new self;
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function delete(string $uri=null, string|callable $action=null){
        try{
            self::pull('delete', $uri, $action);
            return new self;
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function executeBefore($action){
        try{
            array_push(self::$executeBefore,  $action);
            return new self;
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function processRoute(){
        try{
            
            self::initiateRoutes();

            self::$lastRouteAdded = [];
            $data = static::url();

            $_GET = $data['parsed'][0];

            if(!empty(self::$executeBefore)){
                
                foreach(self::$executeBefore as $key => $actionBefore){
                    if(is_callable($actionBefore)){
                        call_user_func($actionBefore);
                    }else{
                        [$controller, $action]   = explode("@",$actionBefore);
                        $controllerWithNamespace = CONTROLLER_NAMESPACE . $controller;

                        if(!class_exists($controllerWithNamespace)){
                            throw new Exception("Este controller {$controller} não existe.",-1);
                        }
                        
                        if(!method_exists($controllerWithNamespace, $action)){
                            throw new Exception("O método {$action} não existe no controller {$controller}.",-1);
                        }
                        $GLOBALS['ROUTE_TYPE'] = $data['uri']['type'];
                        (new $controllerWithNamespace )->$action();
                    }
                }
            }
            
            if(is_callable($data['uri']['action'])){
                call_user_func($data['uri']['action'], ...$data['parsed'][0] );
            }else{
                
                [$controller, $action]   = explode("@", $data['uri']['action']);
                $controllerWithNamespace = CONTROLLER_NAMESPACE . $controller;
    
                if(!class_exists($controllerWithNamespace)){
                    throw new Exception("Este controller {$controller} não existe.",-1);
                }
                
                if(!method_exists($controllerWithNamespace, $action)){
                    throw new Exception("O método {$action} não existe no controller {$controller}.",-1);
                }
                
                $GLOBALS['ROUTE_TYPE'] = $data['uri']['type'];
                (new $controllerWithNamespace)->$action();
            }
            
            self::$executeBefore = [];

        }catch(Exception $e){
            throw $e;
        }
    }

    private static function exactMatchUriInArrayRoutes(string $uri, array $routes, string $method, bool $forApi = false){
        try{

            $uri = $uri === "" ?  "/" : $uri;
            
            $matchedUri = array_filter(
                $routes[$method],
                function($val) use($uri, $forApi){

                    if($forApi){
                        return $val["uri"] == $uri && $val['type'] == "api";
                    }

                    return $val["uri"] == $uri ;
                }
            );

            if( !empty($matchedUri) ){
                $matchedUri = array_values($matchedUri);
                $matchedUri = $matchedUri[0];
            }
            
            return $matchedUri;
        }catch(Exception $e){
            throw $e;
        }
    }

    private static function regularMatchUriInArrayRoutes(string $uri, array $routes, string $type, bool $forApi = false){
        try{

            $matchedUri = array_filter(
                $routes[$type],
                function($val) use($uri, $forApi){
                    if($forApi){
                        return preg_match("/^{$val["regex"]}$/", $uri ) && $val['type'] === 'api';
                    }
                    return preg_match("/^{$val["regex"]}$/", $uri );
                }
            );

            if( !empty($matchedUri) ){
                $matchedUri = array_values($matchedUri);
                $matchedUri = $matchedUri[0];
            }

            return $matchedUri;
        }catch(Exception $e){
            throw $e;
        }
    }

    private static function url(){
        try{

            $uri             = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),"/");
            $type            = strtolower( $_SERVER['REQUEST_METHOD'] );
            $matchedUri      = self::exactMatchUriInArrayRoutes($uri,self::$routes, $type);
            $withAnotherType = false;
            
            if(empty($matchedUri)){
                
                $bTypes = self::$types;

                if (($key = array_search($type, $bTypes)) !== false) {
                    unset($bTypes[$key]);
                }
                foreach($bTypes as $arType){
                    $matchedUri      = self::exactMatchUriInArrayRoutes($uri,self::$routes, $arType, true);
                    if(!empty($matchedUri)) $withAnotherType = true;
                }

                $matchedUri = self::regularMatchUriInArrayRoutes($uri,self::$routes, $type);
            }
            
            if(!empty($matchedUri)){
                $parsed = self::parse($matchedUri['uri']);

                $a1     = explode("/",$parsed[1]);
                $a2     = explode("/",ltrim($uri));

                $diff   = array_diff($a1,$a2);

                foreach($diff as $key => $val){
                    $val      = trim($val, "{}");
                    $a2[$key] = "{{$val}:". $a2[$key] ."}";
                }

                return [
                    "uri"    => $matchedUri,
                    "parsed" => self::parse(join("/",$a2))
                ];
            }else{

                $bTypes = self::$types;

                if (($key = array_search($type, $bTypes)) !== false) {
                    unset($bTypes[$key]);
                }
                foreach($bTypes as $arType){
                    $matchedUri      = self::regularMatchUriInArrayRoutes($uri,self::$routes, $arType, true);
                    if(!empty($matchedUri)) $withAnotherType = true;
                }

                if($withAnotherType){
                    throw new Exception("Rota não encontrada para o método {$_SERVER['REQUEST_METHOD']}." , 405);
                }else{
                    throw new Exception("", 404);
                }
            }

        }catch(Exception $e){
            throw $e;
        }
    }

    private static function parse($uri){
        preg_match_all('/\{([\w\:\W]+?)\??\}/', $uri, $matches);

        $bindingFields = [];

        foreach ($matches[0] as $match) {
            if (! str_contains($match, ':')) {
                continue;
            }

            $segments = explode(':', trim($match, '{}?'));

            $bindingFields[$segments[0]] = $segments[1];

            $uri = str_contains($match, '?')
                ? str_replace($match, '{'.$segments[0].'?}', $uri)
                : str_replace($match, '{'.$segments[0].'}', $uri);
        }

        return [$bindingFields, $uri];
    }
    
    public static function group(string $prefix,callable $callback){
        try{

            array_push(self::$group, trim($prefix,"/"));
            call_user_func($callback);
            array_pop(self::$group);
            
        }catch(Exception $e){
            throw $e;
        }
    }

    public static function redirect(string $rota){
        try{
            $route = self::link($rota);
            header("location: {$route}"  );
        }catch(Exception $e){
            throw $e;
        }
    }

    public function name($alias){
        try{
            
            foreach(self::$lastRouteAdded as $route){
                $key = array_search($route['id'], array_column(self::$routes[$route['method']], "id"));
                self::$routes[$route['method']][$key]['alias'] = $alias;
            }
            self::$lastRouteAdded = [];

            return $this;
        }catch(Exception $e){
            throw $e;
        }
        
    }

    /**
     * Função para retornar o caminho da rota desejada
     * @author Douglas A. Silva
     * @param string $route Rota solicitada, sendo por nome ou uri
     * @return string caminho da rota solicitada
     */
    public static function link(string $route){
        try{
            $links = self::getRouteList(true);

            if(str_contains($route, "/")){
                $indexFound = array_search(trim($route, "/"), array_column($links, "uri"));

                // if(gettype($indexFound) === "integer"){
                //     return ("/" . trim($links[$indexFound]["uri"], "/") );
                // }else{
                //     throw new Exception("Rota não encontrada para a rota '{$route}' ", -1);
                // }
            }else{
                $indexFound = array_search($route, array_column($links, "alias"));
            }

            if(gettype($indexFound) === "integer"){
                return ("/" . preg_replace("/{.*}/m","", trim($links[$indexFound]["uri"], "/") ) );
            }else{
                throw new Exception("Rota não encontrada para a rota '{$route}' ", -1);
            }
            
        }catch(Exception $e){
            throw $e;
        }
    }

    private static function initiateRoutes(){
        try{
            self::$direction = "web";
            require_once(__DIR__ . "/../routes/Web.php");
            self::get("/404","Controller@errorPage");
            
            self::$direction = "api";
            require_once(__DIR__ . "/../routes/Api.php");

            return new self;
        }catch(Exception $e){
            throw $e;
        }
    }

}