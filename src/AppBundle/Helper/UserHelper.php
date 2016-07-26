<?php
/**
 * Created by PhpStorm.
 * User: acalugar
 * Date: 19.07.2016
 * Time: 14:46
 */

namespace AppBundle\Helper;

class UserHelper
{

    public static function generateRandomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function checkCUI($data)
    {
        $data = str_replace('ro', '', strtolower($data));
        $jsonurl = "http://openapi.ro/api/validate/cif/$data.json";
        $json = json_decode(file_get_contents($jsonurl));

        return $json->valid;
    }

    public static function checkIBAN($data)
    {
        $jsonurl = "http://openapi.ro/api/validate/iban/$data.json";
        $json = json_decode(file_get_contents($jsonurl));

        return $json->valid;
    }

}
