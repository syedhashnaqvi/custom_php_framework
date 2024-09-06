<?php

class ContactController {
    public function index(){
        $template = new Template();
        $template->set("title","Contact Us");
        $template->render('contact');
    }
}