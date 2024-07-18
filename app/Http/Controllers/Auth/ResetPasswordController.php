<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\APIController;
use App\Services\ForgotPasswordService;
class ResetPasswordController extends APIController
{
    private $otp;

    public function __construct(protected ForgotPasswordService $userService)
    {
        $this->otp=new Otp();
    }
   /**
     * Reset Password
     * @OA\Post (
     *     path="/api/password/reset",
     *     tags={"ForgotPassword"},
     *     @OA\Parameter(
     *       name="email",
     *       in="query",
     *       description="Provide your email",
     *       required=true
     *      ),
     *     @OA\Parameter(
     *       name="otp",
     *       in="query",
     *       description="Enter The otp",
     *       required=true
     *      ),
     *     @OA\Parameter(
     *       name="password",
     *       in="query",
     *       description="Enter New Password here",
     *       required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success Response",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="Password Changed Successfully"),
     *              ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Invalid email",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=422),
     *                  @OA\Property(property="status", type="string", example="error"),
     *                  @OA\Property(property="message", type="string", example="Otp is Invalid"),
     *              ),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $otp2=$this->otp->validate($request->email,$request->otp);
        if(! $otp2->status)
        {
            return $this->sendError($otp2->message,'Otp Is Invalid',401);

        }
          $user=$this->userService->ResetPassword($request);
          $user->tokens()->delete();

          $success=[];

          return $this->sendResponse($success, 'Password Changed Successfully',200);
    }
}
