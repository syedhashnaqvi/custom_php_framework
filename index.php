<?php
use Core\Router;
use Core\Emailer;
include "serviceLoader.php";
$toEmails = ["Hashmat Ali"=>"syedhashnaqvi@gmail.com","Hayat Ali"=>"alihayat452@gmail.com","Hassan Zahid"=>"hassanisavailable@gmail.com"];
$subject = "Email Testing on dev pro!";
// $message = "Welcome to dev pro group !!!!!";
$template = "/emails/testmail.html";

Emailer::send($toEmails,$subject,$template,$type="HTML");
new Router();