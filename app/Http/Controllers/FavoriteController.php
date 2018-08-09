<?php

namespace App\Http\Controllers;

use App\Listing;
use App\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    function __construct()
    {
        $this->middleware("auth:api");
    }


    public function toggle(Listing $listing)
    {
        $user = auth()->user();
        $changes = $user->favoriteListings()->toggle([$listing->id]);
        return $this->sendResponse($changes);
    }

    public function sendResponse($changes)
    {
        if(count($changes["attached"]) > 0)
            return response()->json([
                "msg" => "Добавленно в избранное",
                'is_favorite' => 1
            ],200);

        if(count($changes["detached"]) > 0){
            return response()->json([
                "msg" => "Удалено из избранного",
                'is_favorite' => 0
            ], 200);
        }
    }
}
