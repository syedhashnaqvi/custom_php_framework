<?php

namespace Core;
use Core\Sessions;
class Validator{
    private static $errors = [];
    public static function validate($validations, $data){
        foreach ($validations as $fieldName => $rules) {
            $rules = explode("|",$rules);
            $isValid = true;
            foreach($rules as $rule){
                switch ($rule) {
                    case 'required':
                        $isValid = self::validateRequired($fieldName,$data);
                    break;
                    case 'email':
                        $isValid = self::validateEmail($fieldName,$data);
                    break;
                }
                if(!$isValid) break;
            }
        }
        Sessions::setMessages(["errors"=>self::$errors]);
        return count(self::$errors)>0 ? false : true;
    }

    private static function validateRequired($fieldName,$data){
        if(!isset($data[$fieldName]) || empty(trim($data[$fieldName]))){
            self::$errors[$fieldName] = "$fieldName is required.";
            return false;
        }
        return true;
    }

    private static function validateEmail($fieldName,$data){
        if(!isset($data[$fieldName]) || empty(trim($data[$fieldName])) || filter_var($data[$fieldName], FILTER_VALIDATE_EMAIL) === false) {
            self::$errors[$fieldName] = "$fieldName is not a valid email address.";
            return false;
        }
        return true;
    }
}


/*========*/
// Hayat
/*========*/
// 1. Number 
// 2. Min
// 3. Max


/*========*/
// Hassan
/*========*/
// 1. Password with confirm Password 
// 2. Pasword length
// 3. Special Chars, Upper case