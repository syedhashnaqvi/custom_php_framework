<?php
namespace Core;

class Config{
    private static $configDir = __DIR__."/../../config/";
    private static $config;

    public static function get($key){
        $key = explode(".",$key);
        self::loadConfigFile($key[0]);
        if(count($key)==1){
            return self::$config;
        }
        return isset(self::$config[$key[1]]) ? self::$config[$key[1]]:null;
    }

    private static function loadConfigFile($fileName){
        if(!isset(self::$config[$fileName])){
            self::$config = require_once(self::$configDir.$fileName.'.php');
        }
    }
}