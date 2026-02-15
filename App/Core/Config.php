<?php
declare(strict_types=1);

namespace Core;

class Config{
    private static string $configDir = __DIR__."/../../config/";
    private static ?array $config = null;

    public static function get(string $key){
        $key = explode(".",$key);
        self::loadConfigFile($key[0]);
        if(count($key)==1){
            return self::$config;
        }
        return isset(self::$config[$key[1]]) ? self::$config[$key[1]]:null;
    }

    private static function loadConfigFile(string $fileName): void{
        if(!isset(self::$config[$fileName])){
            self::$config = include(self::$configDir.$fileName.'.php');
        }
    }
}