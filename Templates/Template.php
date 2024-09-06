<?php

class Template {
    protected $variables = [];

    public function set($name, $value){
        $this->variables[$name] = $value;
    }

    public function render($view){
        extract($this->variables);
        include __DIR__.'/../views/partials/header.php';
        include __DIR__."/../views/$view.php";
        include __DIR__.'/../views/partials/footer.php';
    }
}