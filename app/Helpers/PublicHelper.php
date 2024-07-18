<?php

namespace App\Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Illuminate\Auth\AuthenticationException;
class PublicHelper
{
    // get jwt info from header
    public function GetRawJWT()
    {
        // check if header exists
        if(empty($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new AuthenticationException('Authorization Header Not Found');
        }

        // check if bearer token exists
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            throw new AuthenticationException('Token Not Found');
        }

        // extract token
        $jwt = $matches[1];
        if (!$jwt) {
            throw new AuthenticationException('Could Not Extract Token');
        }

        return $jwt;
    }

    public function DecodeRawJWT($jwt)
    {
        // use secret key to decode token
        $secretKey  = env('JWT_KEY');
        try {
            $token = JWT::decode($jwt, new Key($secretKey, 'HS512'));
            $now = new DateTimeImmutable();
        } catch(Exception $e) {
            throw new AuthenticationException('unauthorized');
        }

        return $token;
    }

    public function GetAndDecodeJWT()
    {
        $jwt = $this->GetRawJWT();
        $token = $this->DecodeRawJWT($jwt);

        return $token;
    }

}
