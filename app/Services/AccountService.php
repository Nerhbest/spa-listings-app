<?php
namespace App\Services;


use Illuminate\Support\Facades\Hash;

class AccountService
{

    public function resolvePasswordStatus($request)
    {
        $user = auth()->user();
        $currentPassword = $user->password;

        if(Hash::check($request["old_password"], $currentPassword)){
            $user->password = Hash::make($request["new_password"]);
            $user->save();
            return response()->json([
                "msg" => "Пароль успешно обновлен"
            ],200);
        }else{
            return response()->json([
                "errors" => [
                    "old_password" => "Неправильный пароль"
                ]
            ],422);
        }
    }
}