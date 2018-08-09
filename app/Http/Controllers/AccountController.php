<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\ListingRepository;
use App\Services\AccountService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    private $listingRepository;
    private $accountService;
    /**
     * @param ListingRepository $listingRepository
     * @param AccountService $accountService
     */
    function __construct(ListingRepository $listingRepository,AccountService $accountService)
    {
        $this->middleware("auth:api");
        $this->listingRepository = $listingRepository;
        $this->accountService = $accountService;

    }

    public function info() {
        $user = auth()->user();
        $listings = $this->listingRepository->loadListingsForUser($user);
        $favoriteListings = $this->listingRepository->loadFavoriteListingsForUser($user);

        return response()->json([
            "accountInfo" => [
                "userInfo" => $user,
                "listingsPaginator" => $listings,
                "favoriteListingsPaginator" => $favoriteListings
            ]
        ]);
    }

    public function getUserListings()
    {
        $user = auth()->user();
        $listingsPaginator = $this->listingRepository->loadListingsForUser($user);
        return response()->json([
            "listingsPaginator" => $listingsPaginator
        ],200);
    }

    public function getUserFavorites()
    {
        $user = auth()->user();
        $favoriteListings = $this->listingRepository->loadFavoriteListingsForUser($user);

        return response()->json([
            "favoriteListingsPaginator" => $favoriteListings
        ],200);

    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->accountService->resolvePasswordStatus($request);
    }

    public function updateCredentials(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user["name"] = $request["name"];
        $user["email"] = $request["email"];
        $user["phone_number"] = $request["phone_number"];

        $user->save();

        return response()->json([
            'msg' => 'Данные успешно обновлены'
        ], 200);
    }
}
