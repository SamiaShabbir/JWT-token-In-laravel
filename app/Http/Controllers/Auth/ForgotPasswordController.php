<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Http\Controllers\APIController;
use App\Services\ForgotPasswordService;

class ForgotPasswordController extends APIController
{
     public function __construct(protected ForgotPasswordService $userService) {}
    /**
     * ForgetPassword
     * @OA\Post (
     *     path="/api/password/forgot",
     *     tags={"ForgotPassword"},
         *     @OA\Parameter(
          *       name="email",
          *       in="query",
          *       description="Provide your email",
          *       required=true
          *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success Response",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="Email Sent Successfully Kindly Check Your Mail"),
     *              ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid email",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=422),
     *                  @OA\Property(property="status", type="string", example="error"),
     *                  @OA\Property(property="message", type="string", example="Email is Invalid"),
     *              ),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      )
     * )
     */
     public function forgotPassword(ForgotPasswordRequest $request)
     {
            $input=$request->only('email');
            $user=$this->userService->FindUserByEmail($input);
            $user->notify(new ResetPasswordNotification());
            $success=[];

            return $this->sendResponse($success, 'Mail Sent To Your Email Kindly Check Mail',200);
     }

}
