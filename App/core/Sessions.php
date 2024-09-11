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
        self::init();
        return isset($_SESSION[$key]);
    }

    public static function sessions($key = null){
        self::init();
        if($key){
            var_dump(isset($_SESSION[$key]) ? $_SESSION[$key] :null);
            return;
        }
        var_dump(isset($_SESSION));
    }

    public static function destroy($key=null){
        self::init();
        if($key){
            unset($_SESSION[$key]);
            return;
        }
        unset($_SESSION);
    }

    public static function message($msg,$type){
        
    }

}

// 1.Session ID function (return current session id)
// 2.Messages (return msg based on type OR can return multple msgs)
// 3.Errors (return signle error or multiple errors)
// 4. Regenerate Session ID