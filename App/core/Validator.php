<?php

namespace Core;
use Core\Sessions;
class Validator{
    private static $errors = [];
    public static function validate($rules, $data){
        foreach ($rules as $fieldName => $rule) {
            switch ($rule) {
                case 'required':
                    self::validateRequired($fieldName,$data);
                break;
                case 'email':
                    self::validateEmail($fieldName,$data);
                break;
            }
        }
        Sessions::setMessages(["errors"=>self::$errors]);
        return count(self::$errors)>0 ? false : true;
    }

    private static function validateRequired($fieldName,$data){
        if(!isset($data[$fieldName]) || empty(trim($data[$fieldName]))) self::$errors[$fieldName] = "$fieldName is required.";
        return;
    }

    private static function validateEmail($fieldName,$data){
        if(!isset($data[$fieldName]) || empty(trim($data[$fieldName])) || filter_var($data[$fieldName], FILTER_VALIDATE_EMAIL) === false) {
            self::$errors[$fieldName] = "$fieldName is not a valid email address.";
            return;
        }
        return;
    }
}