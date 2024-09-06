<?php
namespace Core;
use Core\Request;
use ReflectionMethod;
class Router{
    
    private $requestUri,$requestMethod;

    public function __construct(){
        include __DIR__."/../../routes.php";
        $request = new Request();
        $this->requestUri = $request->get('requestUri');
        $this->requestMethod = $request->get('requestMethod');
        $routeFound = false;
        if(isset($routes[$this->requestMethod])){
            foreach($routes[$this->requestMethod] as $route => $action){
                $routePattern = preg_replace('/:[a-zA-Z0-9]+/','([^/]+)',$route);
                $routePattern = '/^'.str_replace('/','\/',$routePattern).'$/';
                if(preg_match($routePattern,$this->requestUri,$matches)){
                    array_shift($matches);
                    $action = explode("@",$action);
                    $controller = $action[0];
                    $method = $action[1];
                    include __DIR__."/../controllers/$controller.php";
                    $controller = new $controller();
                    $reflectionMethod = new ReflectionMethod($controller,$method);
                    $parameters = $reflectionMethod->getParameters();
                    if(count($parameters)>1){
                        $controller->$method($request,...$matches);
                    }else{
                        $controller->$method(...$matches);
                    }
                    $routeFound = true;
                    break;
                }
            }
        }else{
            $this->methodNotAllowed();
        }

        if(!$routeFound){
            $this->routeNotFound();
        }
    }

    private function methodNotAllowed(){
        http_response_code(405);
        die("405 $this->requestMethod Method not allowed");
    }

    private function routeNotFound(){
        http_response_code(404);
        die("404 NOT FOUND!");
    }
}