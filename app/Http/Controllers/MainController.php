<?php

namespace App\Http\Controllers;

use App\Listing;
use App\Repositories\CategoryRepository;
use App\Repositories\CityRepository;
use App\Services\ListingService;
use Illuminate\Http\Request;

class MainController extends Controller
{

    protected $listingService;
    protected $cityRepository;
    protected $categoryRepository;

    /**
     * @param ListingService $listingService
     * @param CityRepository $cityRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(ListingService $listingService,
                                CityRepository $cityRepository,
                                CategoryRepository $categoryRepository)
    {
        $this->listingService = $listingService;
        $this->categoryRepository = $categoryRepository;
        $this->cityRepository = $cityRepository;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryRepository->getAll();
        $cities = $this->cityRepository->getAll();
        $listingsPaginator = $this->listingService->search($request);

        return response()->json([
                "categories" => $categories,
                "cities" => $cities,
                "listingsPaginator" => $listingsPaginator
        ], 200);
    }
}
