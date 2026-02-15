<?php
declare(strict_types=1);

namespace Core;

use Core\Request;
use Core\Container;
use ReflectionMethod;

class Router{
    
    private string $requestUri;
    private string $requestMethod;

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
                    $controllerName = "App\\Controllers\\" . $action[0];
                    $method = $action[1];
                    $container = new Container();
                    $controller = $container->get($controllerName);
                    $reflectionMethod = new ReflectionMethod($controller,$method);
                    $parameters = $reflectionMethod->getParameters();
                    if(count($parameters)==1 && $parameters[0]->name == "request"){
                        $controller->$method($request);
                    }else if(count($parameters)>1){
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

    private function methodNotAllowed(): void{
        http_response_code(405);
        die("405 $this->requestMethod Method not allowed");
    }

    private function routeNotFound(): void{
        http_response_code(404);
        die("404 NOT FOUND!");
    }
}