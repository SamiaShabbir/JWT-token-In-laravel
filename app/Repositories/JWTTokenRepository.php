<?php

namespace App\Repositories;

use App\Repositories\Interfaces\JWTTokenRepositoryInterface;
use App\Models\JwtTokens;
use Firebase\JWT\JWT;
use DateTimeImmutable;
class JWTTokenRepository implements JWTTokenRepositoryInterface
{
    protected $token;
    public function __construct(JwtTokens $token)
    {
      $this->token=$token;
    }

    public function create($data)
    {
            $secretKey  = env('JWT_KEY');
            $tokenId    = base64_encode(random_bytes(16));
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();
            $serverName = "localserver";
            $userID   = $data['id'];

            // Create the token as an array
            $payload = [
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
            $JWTtoken = JWT::encode(
                $payload,
                $secretKey,
                'HS512'
            );

            $insertArray=array(
            'token'=>$JWTtoken,
            'token_type'=>'Bearer',
            'last_used'=>$issuedAt,
            'is_valid'=>1,
            'tokenable_id'=>$tokenId
            );

            $this->token->insert($insertArray);
            return $JWTtoken;
    }
    public function updateonlystatus($id)
    {
             $token=$this->token->where('tokenable_id',$id)->update(['tokenable_id'=>0]);
             return $token;
    }

    public function find($id)
    {

    }

    public function delete($id)
    {

    }

    public function update($data)
    {
              $token=$this->token->where([['tokenable_id',$data->jti],['is_valid','1']])->first();

                          $secretKey  = env('JWT_KEY');
                          $tokenId    = base64_encode(random_bytes(16));
                          $issuedAt   = new DateTimeImmutable();
                          $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();
                          $serverName = "localserver";
                          $userID   = $data->data->userID;

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
                          $Jwttoken = JWT::encode(
                              $data,
                              $secretKey,
                              'HS512'
                          );
                 $token=$token->update(['tokenable_id'=>$tokenId,'token'=>$Jwttoken,'last_used'=>$issuedAt]);
                 return $Jwttoken;

    }

}
