<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all();
    public function create(array $array);
    public function update(array $array,$id);
    public function delete($id);
    public function find($id);
    public function auth(array $array,$remember=null);
    public function findByemail(string $email);
}
