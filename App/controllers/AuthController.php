<?php
use Core\Validator;
use Core\Hash;
use Core\DB;
use Core\Sessions;
use Core\Auth;
class AuthController {
    public function register(){
        $template = new Template();
        $template->set("title","Register User");
        $template->render('register');
    }

    public function store($request){
        $data = $request->except("confrim");
        $isValid = Validator::validate([
            "email" => "required|email",
            "username" => "required",
        ],$data);

        if(!$isValid){
            redirect("register");
        }
        $data["password"] = Hash::makeHash($data["password"]);

        $user = DB::table("users")->insert($data);
        if(!$user){
            Sessions::setMessages(["error"=>"Something went wrong!"]);
        }else{
            // dd($user);
            Sessions::setMessages(["success"=>"User created successfully!"]);
        }
        redirect("register");
    }

    public function login(){
        $template = new Template();
        $template->set("title","Login User");
        $template->render('login');
    }


    public function verifyLogin($request){
        $credentials = $request->all();
        $isValid = Validator::validate([
            "email" => "required|email",
            "password" => "required",
        ],$credentials);

        if(!$isValid){
            redirect("login");
        }

        $authenticated = Auth::attemptLogin($credentials);
        if(!$authenticated) redirect("login");
        echo "Welcome on board!";
    }
}