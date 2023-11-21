<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller
{

    /**
    * @OA\Post(
    *     path="/api/register",
    *     tags={"auth"},
    *     summary="Register Auth",
    *     description="Authenticate user by email and password",
    *     operationId="register",
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="name",
    *                     type="string",
    *                     description="User's name"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string",
    *                     description="User's email address"
    *                 ),
    *                 @OA\Property(
    *                     property="password",
    *                     type="string",
    *                     format="password",
    *                     description="User's password"
    *                 ),
    *                 required={"name", "email", "password"}
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Authentication successful",
    *     
    *     ),
    * )
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"auth"},
     *     summary="Login Auth",
     *     description="Authenticate user by email and password",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User's email address"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User's password"
     *                 ),
     *                 required={"email", "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *         
     *     ),
     * )
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"auth"},
     *     summary="Logout Auth",
     *     description="Logout and revoke access token",
     *     security={
     *      {"sanctum": {}}
     *     },
     *     operationId="logout",
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out and token deleted",
     *        
     *     )
     * )
     */
    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
            return [
                'message' => 'You have successfully logged out, and the token was successfully deleted'
            ];
        } else {
            return [
                'message' => 'No authenticated user found',
            ];
        }
    }
}