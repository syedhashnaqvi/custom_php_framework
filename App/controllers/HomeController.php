<?php
use Core\Sessions;
class HomeController {
    public function index(){

        Sessions::set("user","koi bhi user");
        $result = Sessions::get("user");
        dd($result);
        $template = new Template();
        $template->set("title","Home");
        $template->set("details","Welcome to the future !");
        $template->render('home');
    }
}