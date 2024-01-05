<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register The User",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="postRegister",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name","email","password","dob","gender","mobile"},
     *                 @OA\Property(property="name",type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *                 @OA\Property(property="dob", type="string",description="Ex. 27-07-2022(d-m-Y)"),
     *                 @OA\Property(property="gender", type="string"),
     *                 @OA\Property(property="mobile", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'email' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {

            return User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "dob" => $request->dob,
                "gender" => $request->gender,
                "mobile" => $request->mobile,
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login for user",
     *     description="Login api for users",
     *     operationId="postLogin",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email","password"},
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function login(Request $request)
    {

        $rules = array(
            'password' => 'required',
            'email' => 'required|email',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {

            if (!Auth::attempt($request->only("email", "password"))) {
                return response([
                    'message' => 'Invalid Credentials!'
                ], Response::HTTP_UNAUTHORIZED);
            }
            $user = Auth::user();

            $token = $user->createToken('token')->plainTextToken;

            $data = [];
            $data['access_token'] = $token;
            $data['token_type'] = 'bearer';
            $data['user'] = $user;

            $cookie = cookie('jwt', $token, 60 * 24);

            return response([
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ])->withCookie($cookie);
        }
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }
}
