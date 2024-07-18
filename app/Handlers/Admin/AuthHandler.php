<?php

namespace App\Handlers\Admin;

use Firebase\JWT\JWT;
use DateTimeImmutable;
use App\Models\JwtTokens;
use App\Helpers\PublicHelper;
class AuthHandler
{
    /**
     * Handles operations related to admin authentication
     */

    // generate token custom
    public function GenerateToken($user)
    {
        $secretKey  = env('JWT_KEY');
        $tokenId    = base64_encode(random_bytes(16));
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();
        $serverName = "localserver";
        $userID   = $user->id;

        // Create the token as an array
        $data = [
            'iat'  => $issuedAt->getTimestamp(),
            'jti'  => $tokenId,
            'iss'  => $serverName,
            'nbf'  => $issuedAt->getTimestamp(),
            'exp'  => $expire,
            'data' => [
                'userID' => $userID,
            ]
        ];

    // Encode the array to a JWT string.
        $token = JWT::encode(
            $data,
            $secretKey,
            'HS512'
        );
        $insertArray=array(
        'token'=>$token,
        'token_type'=>'Bearer',
        'last_used'=>$issuedAt,
        'is_valid'=>1,
        'tokenable_id'=>$tokenId
        );

        JwtTokens::insert($insertArray);
        return $token;
    }
    public function RegenerateToken()
    {



    }
}
