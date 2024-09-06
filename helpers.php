<?php
// Dump & Die function
function dd(...$vars){
    echo "<pre style='background:#000000;color:#00FF00;font-size:16px;'>";
    foreach ($vars as $var) {
        var_dump($var);
        echo "<br>";
    }
    echo "</pre>";
    die();
}

// Dump data
function dump(...$vars){
    echo "<pre style='background:#000000;color:#00FF00;font-size:16px;'>";
    foreach ($vars as $var) {
        var_dump($var);
        echo "<br>";
    }
    echo "</pre>";
}

// for output
function __(...$vars){
    foreach ($vars as $var) {
        echo $var;
    }
}