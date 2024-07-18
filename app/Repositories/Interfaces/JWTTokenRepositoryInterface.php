<?php

namespace App\Repositories\Interfaces;

interface JWTTokenRepositoryInterface
{
       public function create(array $data);
       public function updateonlystatus($id);
       public function find($id);
       public function delete($id);
       public function update(array $data);
}
