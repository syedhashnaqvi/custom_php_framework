<?php
namespace Core;
use Core\Sessions;
class Request{
    private $queryData,$postData,$requestHeaders,$requestUri,$requestMethod;

    public function __construct(){
        $this->queryData = $_GET;
        $this->postData = $_POST;
        $this->requestHeaders = getallheaders();
        $this->requestUri = $_SERVER["REQUEST_URI"];
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if($this->requestMethod == "POST") Sessions::set("form_old_data",$_POST);
    }

    public function all(){
        return array_merge($this->queryData,$this->postData);
    }

    public function except(...$params){
        $exceptArr = $this->all();
        foreach ($params as $key) {
            unset($exceptArr[$key]);
        }
        return $exceptArr;
    }

    public function get($attrName){
        return $this->$attrName;
    }
}