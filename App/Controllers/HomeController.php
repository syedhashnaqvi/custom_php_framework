<?php

namespace App\Controllers;

use Core\Sessions;
use Templates\Template;

class HomeController {
    public function index(){
        $template = new Template();
        $template->render('welcome', false);
    }
}