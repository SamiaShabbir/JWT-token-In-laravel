<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    protected $user;
    public function __construct(User $user)
    {
         $this->user=$user;
    }

    public function all()
    {
        return $this->user->get();
    }

    public function find($id)
    {
       return $this->user->find($id);
    }

    public function create($data)
    {
      $user=new $this->user;
      $user->email=$data['email'];
      $user->name=$data['name'];
      $user->password=Hash::make($data['password']);
      $user->save();

      return $user->fresh();
    }

    public function update($data,$id)
    {
      $user=$this->user->find($id);
      $user->email=$data['email'] ?? $user->email;
      $user->name=$data['name'] ?? $user->name;
      $user->password=Hash::make($data['password']) ?? $user->password;

      $user->update();
      return $user;
    }

    public function delete($id)
    {
      $user=$this->user->find($id);
      $user->delete();
    }

    public function auth($data,$remember=null)
    {
       Auth::attempt($data, $remember);
       return Auth::user();
    }

    public function findByemail($email)
    {
      $user=$this->user->where('email',$email)->first();
      return $user;
    }

}
