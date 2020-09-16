<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 22:23
 */

namespace App\Utilities;

use \Firebase\JWT\JWT;

class JwtUtilities
{
    public static function generateNewToken($id) {
        $privateKey = file_get_contents(__DIR__ . '/jwtRS256.key');
        $token = array(
            "id" => $id,
            "iss" => "cloud.xyz.pl",
            "aud" => ["cloud.xyz.pl"],
            "iat" => time(),
            "exp" =>  time() + 60
        );

        $jwt = JWT::encode($token, $privateKey, 'RS256');
        return $jwt;
    }

    public static function validateToken($token) {
        $publicKey = file_get_contents(__DIR__ . '/jwtRS256.key.pub');
        try {
            if(JWT::decode($token, $publicKey, array('RS256')))
                return true;
        }
        catch (\Exception $exception) {
            return false;
        }
    }

    public static function getTokenData($token) {
        $publicKey = file_get_contents(__DIR__ . '/jwtRS256.key.pub');
        try {
            $decoded = JWT::decode($token, $publicKey, array('RS256'));
            if($decoded) {
                return (array)$decoded;
            }
        }
        catch (\Exception $exception) {
            return null;
        }
    }
}