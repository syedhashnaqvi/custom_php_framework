<?php
namespace Core;
class Sessions {
    private static $obj;

    public function __construct(){
        session_start();
    }

    public static function init(){
        if(is_null(self::$obj)){
            self::$obj = new self();
        }
        return self::$obj;
    }

    public static function set($key,$value){
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key){
        self::init();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
    }

    public static function exists($key){
        return isset($_SESSION[$key]);
    }

}

// TASKS
// 1. Session Exists function to check if a session exists or not
// returns true of false

// 2. View Sessiong fucntion to display all sessions data or a specific key based data

// 3. Destroy session function to destroy all sessions or just one with key provided