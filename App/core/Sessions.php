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
        // if(isset($_SESSION[$key])) return;
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

    public static function sessionId(){
        self::init();
        return session_id();        
    }

    public static function regenerateId(){
        session_regenerate_id();
        return session_id();
    }

    public static function messages($key = null) {
        $result = self::get("messages");
        self::destroy("messages");
        return $key ? (isset($result[$key]) ? $result[$key] : null):$result;
    }
    public static function setMessages($msg) {
        self::set("messages",$msg);
    }
}
