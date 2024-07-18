<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtTokens extends Model
{
    use HasFactory;
    protected $fillable = [
            'token',
            'tokenable_id',
            'is_valid',
            'last_used'
    ];
}
