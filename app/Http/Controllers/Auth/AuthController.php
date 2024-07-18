<?php

namespace App\Http\Controllers\Auth;

use App\Handlers\Admin\AuthHandler;
use App\Models\User;
use Firebase\JWT\JWT;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Validator;
use App\Handlers\Admin\VerifyToken;
use App\Http\Requests\Auth\loginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="JWT Token and Swagger With Laravel",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="darius@matulionis.lt"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class AuthController extends APIController
{
         public function __construct(protected UserService $userService) {}
        /**
         * Register
         * @OA\Post (
         *     path="/api/register",
         *     tags={"Auth"},
         *     @OA\Parameter(
         *       name="name",
         *       in="query",
         *       description="Provide your name",
         *       required=true
         *      ),
         *     @OA\Parameter(
         *       name="email",
         *       in="query",
         *       description="Provide your email",
         *       required=true
         *      ),
         *     @OA\Parameter(
         *       name="password",
         *       in="query",
         *       description="Provide your password",
         *       required=true
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="Success",
         *          @OA\JsonContent(
         *              @OA\Property(property="meta", type="object",
         *                  @OA\Property(property="code", type="number", example=200),
         *                  @OA\Property(property="status", type="string", example="success"),
         *                  @OA\Property(property="message", type="string", example=null),
         *              ),
         *              @OA\Property(property="data", type="object",
         *                  @OA\Property(property="user", type="object",
         *                      @OA\Property(property="id", type="number", example=1),
         *                      @OA\Property(property="name", type="string", example="John"),
         *                      @OA\Property(property="email", type="string", example="john@test.com"),
         *                      @OA\Property(property="email_verified_at", type="string", example=null),
         *                      @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
         *                      @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
         *                  ),
         *                  @OA\Property(property="access_token", type="object",
         *                      @OA\Property(property="token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
         *                      @OA\Property(property="type", type="string", example="Bearer"),
         *                      @OA\Property(property="expires_in", type="number", example=3600),
         *                  ),
         *              ),
         *          )
         *      ),
         *      @OA\Response(
         *          response=422,
         *          description="Validation error",
         *          @OA\JsonContent(
         *              @OA\Property(property="meta", type="object",
         *                  @OA\Property(property="code", type="number", example=422),
         *                  @OA\Property(property="status", type="string", example="error"),
         *                  @OA\Property(property="message", type="object",
         *                      @OA\Property(property="email", type="array", collectionFormat="multi",
         *                        @OA\Items(
         *                          type="string",
         *                          example="The email has already been taken.",
         *                          )
         *                      ),
         *                  ),
         *              ),
         *              @OA\Property(property="data", type="object", example={}),
         *          )
         *      )
         * )
         */
    // register
        public function register(RegisterRequest $request)
        {
            $input = $request->only('name', 'email', 'password');

            $CreateUser=$this->userService->CreateUser($input);

            if($CreateUser==null)
            {
                return $this->sendError('Not Created', ['error' => "Soemthing went wrong try again"], 401);
            }

              return $this->sendResponse($success, 'User Registered Successfully', 201);

        }

/**
 * Login
 * @OA\Post (
 *     path="/api/login",
 *     tags={"Auth"},
     *     @OA\Parameter(
      *       name="email",
      *       in="query",
      *       description="Provide your email",
      *       required=true
      *      ),
      *     @OA\Parameter(
      *       name="password",
      *       in="query",
      *       description="Provide your password",
      *       required=true
      *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Valid credentials",
 *          @OA\JsonContent(
 *              @OA\Property(property="meta", type="object",
 *                  @OA\Property(property="code", type="number", example=200),
 *                  @OA\Property(property="status", type="string", example="success"),
 *                  @OA\Property(property="message", type="string", example=null),
 *              ),
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="user", type="object",
 *                      @OA\Property(property="id", type="number", example=2),
 *                      @OA\Property(property="name", type="string", example="User"),
 *                      @OA\Property(property="email", type="string", example="user@test.com"),
 *                      @OA\Property(property="email_verified_at", type="string", example=null),
 *                      @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *                      @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *                  ),
 *                  @OA\Property(property="access_token", type="object",
 *                      @OA\Property(property="token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
 *                      @OA\Property(property="type", type="string", example="Bearer"),
 *                      @OA\Property(property="expires_in", type="number", example=3600),
 *                  ),
 *              ),
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Invalid credentials",
 *          @OA\JsonContent(
 *              @OA\Property(property="meta", type="object",
 *                  @OA\Property(property="code", type="number", example=401),
 *                  @OA\Property(property="status", type="string", example="error"),
 *                  @OA\Property(property="message", type="string", example="Incorrect username or password!"),
 *              ),
 *              @OA\Property(property="data", type="object", example={}),
 *          )
 *      )
 * )
 */

    public function login(loginRequest $request)
    {
            $input = $request->only('email', 'password');

            $AuthUser=$this->userService->Login($input);

            if($AuthUser==null)
            {
                  return $this->sendError('Unauthorized', ['error' => "Invalid Login credentials"], 401);


            }

            return $this->sendResponse($AuthUser, 'User Logged In Successfully',200);

    }
     /** Logout
     * @OA\Post (
     *     path="/api/logout",
     *     tags={"Auth"},
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="User logged out successfully"),
     *              ),
     *
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=422),
     *                  @OA\Property(property="status", type="string", example="error"),
     *
     *              ),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *     security={{"bearerAuth": {} }}
     * )
     */

      public function logout()
      {
             $deletetoken=$this->userService->Logout();
             if($deletetoken==true)
             {
                return $this->sendResponse('','User Logged Out Successfully',200);
             }
      }
 /**
     * Refresh
     * @OA\Get (
     *     path="/api/refresh",
     *     tags={"Auth"},
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
     *          response=403,
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
    public function refresh()
    {
            $authHandler = new AuthHandler;
           $token=$this->userService->RegenerateToken();
           if($token==false)
           {
              return $this->sendError('','Token not generated try again',400);
           }
          else
          {
             $success=['token'=>$token];
            return $this->sendResponse($success, 'Token Regenerated Successfully');

          }
    }

}
