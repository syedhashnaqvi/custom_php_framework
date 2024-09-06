<?php

class HomeController {
    public function index(){
        $template = new Template();
        $template->set("title","Home");
        $template->set("details","Welcome to the future !");
        $template->render('home');
    }
}