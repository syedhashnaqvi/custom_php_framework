<?php
declare(strict_types=1);

namespace Core;

use Core\Sessions;

class Request{
    private array $queryData;
    private array $postData;
    private array $requestHeaders;
    private string $requestUri;
    private string $requestMethod;

    public function __construct(){
        $this->queryData = $_GET;
        $this->postData = $_POST;
        $this->requestHeaders = getallheaders() ?: [];
        $this->requestUri = $_SERVER["REQUEST_URI"];
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if($this->requestMethod == "POST") Sessions::set("form_old_data",$_POST);
    }

    public function all(): array{
        return array_merge($this->queryData,$this->postData);
    }

    public function except(string ...$params): array{
        $exceptArr = $this->all();
        foreach ($params as $key) {
            unset($exceptArr[$key]);
        }
        return $exceptArr;
    }

    public function get(string $attrName){
        return $this->$attrName;
    }
}