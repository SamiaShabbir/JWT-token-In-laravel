<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use App\Services\UserService;
class HomeController extends APIController
{
          public function __construct(protected UserService $userService)
          {

          }
         /**
         * Home
         * @OA\Get (
         *     path="/api/home",
         *     tags={"Home"},
         *      @OA\Response(
         *          response=200,
         *          description="Success",
         *          @OA\JsonContent(
         *              @OA\Property(property="meta", type="object",
         *                  @OA\Property(property="code", type="number", example=200),
         *                  @OA\Property(property="status", type="string", example="success"),
         *                  @OA\Property(property="message", type="string", example="User token regenerated successfully"),
         *              ),
         *
         *              ),
         *          )
         *      ),
         *      @OA\Response(
         *          response=404,
         *          description="Validation error",
         *          @OA\JsonContent(
         *              @OA\Property(property="meta", type="object",
         *                  @OA\Property(property="code", type="number", example=404),
         *                  @OA\Property(property="status", type="string", example="error"),
         *
         *              ),
         *              @OA\Property(property="data", type="object", example={}),
         *          )
         *      ),
         *     security={{"bearerAuth": {} }}
         * )
         */
         public function home()
        {
            $data = [
                'page' => "This is the home page"
            ];

            $message = "You found me!";

            return $this->sendResponse($data, $message);
        }
         /**
         * User
         * @OA\Get (
         *     path="/api/user",
         *     tags={"Home"},
         *      @OA\Response(
         *          response=200,
         *          description="Success",
         *          @OA\JsonContent(
         *              @OA\Property(property="meta", type="object",
         *                  @OA\Property(property="code", type="number", example=200),
         *                  @OA\Property(property="status", type="string", example="success"),
         *                  @OA\Property(property="message", type="string", example="User Info Fetched Successfully"),
         *              ),
         * @OA\Property(property="data", type="object",
         *                  @OA\Property(property="user", type="object",
         *                      @OA\Property(property="id", type="number", example=2),
         *                      @OA\Property(property="name", type="string", example="User"),
         *                      @OA\Property(property="email", type="string", example="user@test.com"),
         *                      @OA\Property(property="email_verified_at", type="string", example=null),
         *                      @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
         *                      @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
         *                  ),
         *              ),
         *          )
         *      ),
         *      @OA\Response(
         *          response=400,
         *          description="Validation error",
         *          @OA\JsonContent(
         *              @OA\Property(property="meta", type="object",
         *                  @OA\Property(property="code", type="number", example=401),
         *                  @OA\Property(property="status", type="string", example="error"),
         *
         *              ),
         *              @OA\Property(property="data", type="object", example={}),
         *          )
         *      ),
         *     security={{"bearerAuth": {} }}
         * )
         */
         public function user()
         {
             $user=$this->userService->GetUserById();
             if($user==false)
             {
                return $this->sendError('','User Not Found',400);
             }
             $message="User Data  Fetched Successfully";

             return $this->sendResponse($user, $message);

         }
}
