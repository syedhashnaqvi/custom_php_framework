<?php
namespace Core;
use Core\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

class Emailer {
    public static function send($to,$subject,$message,$type = "text"){
        $mail = new PHPMailer(true);
        try {

            if(Config::get("mail.debug")){
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            }
            $mail->isSMTP();                                           
            $mail->Host       = Config::get("mail.host");                    
            $mail->SMTPAuth   = true;                                 
            $mail->Username   = Config::get("mail.user");                     
            $mail->Password   = Config::get("mail.password");                             
            $mail->SMTPSecure = Config::get("mail.encryption");           
            $mail->Port       = Config::get("mail.port");                                 

            //Recipients
            $mail->setFrom(Config::get("mail.from_email"), Config::get("mail.from_name"));
            if(is_array($to)){
                foreach ($to as $key => $email) {
                    if(is_string($key)){
                        $mail->addAddress($email,$key);
                    }else{
                        $mail->addAddress($email);
                    }
                }
            }else{
                $mail->addAddress($to);     
            }

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            // $mail->Body = $message;
            $body = '';
            if($type == "text"){
                $body = $message;
            }else{
                $body = file_get_contents(__DIR__."/../../views".$message);
            }
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}