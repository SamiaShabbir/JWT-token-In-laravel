<?php

namespace App\Services;
use App\Repositories\Interfaces\UserRepositoryInterface;

class ForgotPasswordService
{
    public function __construct(protected UserRepositoryInterface $UserRepository)
    {

    }

    public function FindUserByEmail($email)
    {
      $user=$this->UserRepository->findByemail($email);
      if($user)
      {
          return $user;
      }
      else
      {
         return false;
      }
    }

    public function ResetPassword($data)
    {
       $user=$this->UserRepository->findByemail($data['email']);
       $updateuser=$this->UserRepository->update($data,$user->id);
       return $updateuser;
    }
}
