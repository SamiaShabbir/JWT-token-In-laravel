<?php

namespace App\Handlers\Admin;

use Firebase\JWT\JWT;
use DateTimeImmutable;
use App\Helpers\PublicHelper;
use App\Models\JwtTokens;
use App\Models\User;

class VerifyToken
{
    /**
     * Handles operations related to admin authentication
     */

    // generate token custom
    public function VerifyToken($token)
    {
      $findtoken=JwtTokens::where([['tokenable_id',$token->jti],['is_valid','1']])->first();
      if($findtoken)
      {
          $finduser=User::find($token->data->userID);
          $timedate=new DateTimeImmutable();
          $updatetoken=$findtoken->update(['last_used'=>$timedate]);
           if($updatetoken && $finduser)
           {
               return true;
           }
           else
           {
               return false;
           }
      }
    }

    // delete token
    public function DeleteToken()
    {
          $PublicHelper=new PublicHelper();
          $token = $PublicHelper->GetAndDecodeJWT();

          $findtoken=JwtTokens::where([['tokenable_id',$token->jti],['is_valid','1']])->first();
          if($findtoken)
          {
              $timedate=new DateTimeImmutable();
              $updatetoken=$findtoken->update(['is_valid'=>0]);
               if($updatetoken)
               {
                   return true;
               }
               else
               {
                   return false;
               }
          }

    }
}
