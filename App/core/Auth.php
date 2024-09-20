<?php
namespace Core;
use Core\DB;
use Core\Hash;
class Auth {
    public static function attemptLogin($credentials){
        $userData = DB::table("users")->select()->where("email",$credentials['email'])->first();
        if(!$userData){
            Sessions::setMessages(["error"=>"User does not exists!"]);
            return false;
        }
        if(!Hash::verifyHash($credentials["password"],$userData[0]->password)){
            Sessions::setMessages(["error"=>"Invalid Credentials!"]);
            return false;
        }
        Sessions::set("user",$userData);
        return true;
    }
}