<?php
include "helpers.php";

function autoLoader($className){
    $directories = ['App/','Templates/'];
    $className = str_replace('\\',DIRECTORY_SEPARATOR,$className);
    foreach ($directories as $directory) {
        $file = $directory.$className.'.php';
        if(file_exists($file)){
            require_once $file;
            return;
        }
    }
    require_once("core/".$className.".php");
}

spl_autoload_register('autoLoader');


