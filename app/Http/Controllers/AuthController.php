<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    function __construct()
    {
        $this->middleware("auth:api")->only(['logout']);
    }

    public function register(RegisterRequest $request)
    {

        $user = User::alreadyExists($request["email"], $request["phone_number"])->first();
        if($user){
            return response()->json($user->resolveErrors($request), 409);
        }else{
            $user = User::create([
                "name" => $request["name"],
                "email" => $request["email"],
                "password" => bcrypt($request["password"]),
                "phone_number" => $request["phone_number"]
            ]);

            return response()->json([
                "msg" => "Вы успешно зарегистрированы"
            ], 200);
        }
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->only(['email', "password"]);

        if(! $token = auth()->attempt($credentials)){
            return $this->respondWithLoginError();
        }

        return $this->respondWithToken($token);

    }

    public function respondWithLoginError()
    {
        $user = auth()->guard()->getLastAttempted();

        if($user){
            return response()->json([
                "errors" => [
                    "password" => "Неверный пароль"
                ]
            ], 409);
        }

        return response()->json([
            "errors" => [
                "email" => "Пользователя с такими данными не существует"
            ]
        ], 409);
    }


    public function logout()
    {
        auth()->logout();

        return response()->json([
            "msg" => "Вы вышли из аккаунта"
        ], 200);
    }

    public function respondWithToken($token)
    {
        $user = auth()->guard()->getLastAttempted();

        return [
            "token" => $token,
            "expires_in" => JWTAuth::setToken($token)->getPayload()['exp'],
            "user" => [
                "username" => $user->name,
                "id" => $user->id
            ],
            "msg" => "Добро Пожаловать"
        ];
    }
}
