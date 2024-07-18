<?php

namespace App\Http\Middleware;

use App\Helpers\PublicHelper;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use App\Handlers\Admin\VerifyToken;

class JWTVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $publicHelper = new PublicHelper();

        try {
            $token = $publicHelper->GetAndDecodeJWT();
            if($token)
            {
              $VerifyToken=new VerifyToken();
              $result=$VerifyToken->VerifyToken($token);
              if($result==false)
              {
                return response()
                      ->json(
                       ['status'=>'failed','code'=>401,'error'=>'Invalid Token'],401);
              }
            }
        } catch (Exception $e) {
            return response()
                   ->json(
                   ['status'=>'failed','code'=>401,'error' =>'Token is Unauthorized'],401);
        }

        return $next($request);
    }
}
