<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class Ulti
{
    public static function setCookie($name, $value, $minutes)
    {

        // $minutes = 60;
        // $response = new Response('Set Cookie');
        // $response->withCookie(cookie($name, $value, $minutes));
        // return $response;
        setcookie($name, $value, time() + 2 * 24 * 60 * 60);
    }
    public function getCookie(Request $request, $name)
    {
        $value = $request->cookie($name);
        return $value;
    }
    public static function genJWT($id){
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        // Create token payload as a JSON string
        $payload = json_encode(['user_id' => $id, "expiration" => date("Y-m-d")]);
        
        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }
}

