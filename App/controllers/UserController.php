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
        // $validationRules = [
        //     "username" => "required",
        //     "email" => "email",
        // ];
        // $isValid = Validator::validate($validationRules,$data);

        $isValid = Validator::validate([
            "username" => "required",
            "email" => "email",
        ],$data);
        if(!$isValid){
            redirect("user/5");
        }
        dd("Valid");
        // $result = DB::table("admins")->select("email")->orderBy("id","DESC")->get();
        // $result = DB::table("admins")->select()->find(6);
        // $result = DB::table("admins")->insert($data);
        // $result = DB::table("admins")->delete("id",4);
        // $result = DB::table("admins")->query("Delete from admins where id = 3")->execute();
        // $data=[
        //     "username" => "Ali Bangash",
        //     "email" => "ali@gmail.com",
        //     "password" => "123456789",
        // ];
        // $result = DB::table("admins")->update($data,9);
        // $result = DB::table("admins")->select()->where("id",9)->get();
        // $result1 = DB::table("admins")->select()->where("email","LIKE","%@vw%")->get();
        // $result2 = DB::table("admins")->select()->where(["email","like","%@vw%"])->get();
        // $result3 = DB::table("admins")->select()->where([["email","like","%@vw%"]])->get();
        // $result = DB::table("admins")->select()->where([["email","like","%@vw%"],["username","=","Hayat Ali"],["password","=","abc123"]])->get();


        // $result = DB::table("users")->select()->leftJoin("accounts","users.id","accounts.user_id")->where("users.id",9)->get();
        $result = Sessions::get("user");
        dd($result);
    }

    
}