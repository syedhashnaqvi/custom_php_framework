<?php

namespace Templates;

class Template {
    protected $variables = [];

    public function set($name, $value){
        $this->variables[$name] = $value;
    }

    public function render($view, $useLayout = true){
        extract($this->variables);
        if ($useLayout) {
            include __DIR__.'/../views/partials/header.php';
        }
        include __DIR__."/../views/$view.php";
        if ($useLayout) {
            include __DIR__.'/../views/partials/footer.php';
        }
    }
}