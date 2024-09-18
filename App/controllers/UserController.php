<?php
use Core\DB;
use Core\Sessions;
use Core\Validator;
class UserController{
    public function show($id){
        $template = new Template();
        $template->set("title","User");
        $template->set("user_id",$id);
        $template->set("details","Showing details of user with id $id");
        $template->render('user');
    }

    public function update($request,$id){
        $data = $request->all();
        $isValid = Validator::validate([
            "email" => "required|email",
            "username" => "required",
        ],$data);

        if(!$isValid){
            redirect("user/5");
        }
        
        dd("Valid");
    }

    
}