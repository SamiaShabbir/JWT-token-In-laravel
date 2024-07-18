<?php

namespace App\Services;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\JWTTokenRepositoryInterface;
use App\Helpers\PublicHelper;
use App\Helpers\AdminHelper;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository,protected JWTTokenRepositoryInterface $JWTTokenRepository)
    {

    }

    public function CreateUser($data)
    {
        $CreatedUser = $this->userRepository->create($data);

        if($CreatedUser)
        {
            $CreateToken=$this->JWTTokenRepository->create($CreatedUser);
        }

        $result = [
                        'user' => $CreatedUser,
                        'token' => $CreateToken
                  ];
        if($CreatedUser && $CreateToken)
        {
           return $result;
        }
        else
        {
           return null;
        }

    }

    public function Login($data)
    {
       $loginuser=$this->userRepository->auth($data);
       if($loginuser)
       {
           $CreateToken=$this->JWTTokenRepository->create($loginuser);

           $result = [
                      'user' => $loginuser,
                      'token' => $CreateToken
                  ];
       }

        if($loginuser && $CreateToken)
        {
          return $result;

        }

        return null;

    }

     public function Logout()
     {
          $PublicHelper=new PublicHelper();
          $token = $PublicHelper->GetAndDecodeJWT();

          $updatedToken=$this->JWTTokenRepository->updateonlystatus($token->jti);
          if($updatedToken)
          {
             return true;
          }
          else
          {
             return false;
          }

     }

     public function RegenerateToken()
     {
          $PublicHelper=new PublicHelper();
          $token = $PublicHelper->GetAndDecodeJWT();
          $newToken=$this->JWTTokenRepository->update($token);
          if($newToken)
          {
            return $newToken;
          }
          else
          {
            return false;
          }


     }
     public function GetUserById()
     {
        $publicHelper = new PublicHelper();
        $token = $publicHelper->GetAndDecodeJWT();
        $user=$this->userRepository->find($token->data->userID);
        if($user)
        {
                return $user;

        }
        else
        {
               return false;
        }

     }

}
