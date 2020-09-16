<?php

namespace TwitterClone\Validate;
class Validate  
{
    public static function escape($input)
    {
        $input = trim(strip_tags($input));
        $input = stripslashes($input);
        $input = htmlentities($input, ENT_QUOTES);
        $input = htmlspecialchars($input);
        return $input;
    }

    public static function filterEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function length($input, $min, $max)
    {
        if (strlen($input) > $max) {
            return true;
        } elseif (strlen($input) < $min) {
            return true;
        }
        
    }

    public static function validName($name)
    {
        return preg_match("/^[a-zA-Z-.'\s]+$/", $name);
    }

    public static function validUsername($username)
    {
        return preg_match("/[^a-zA-Z0-9\!]/", $username);
    }
   
}


?>