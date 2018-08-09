<?php
namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Filters\Listing\Transformers\CategoryTransformer;
use App\Http\Requests\CreateListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Http\Requests\UploadPhotoRequest;
use App\Listing;
use App\ListingImage;
use App\Repositories\CategoryRepository;
use App\Repositories\CityRepository;
use App\Services\ListingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ListingController extends Controller
{
    private $listingService;
    private $categoryRepository;
    private $cityRepository;

    /**
     * @param ListingService $listingService
     * @param CategoryRepository $categoryRepository
     * @param CityRepository $cityRepository
     */
    function __construct(ListingService $listingService,
                         CategoryRepository $categoryRepository,
                         CityRepository $cityRepository)
    {
        $this->middleware('auth:api')->except(['index', 'search', 'show']);

        $this->listingService = $listingService;
        $this->categoryRepository = $categoryRepository;
        $this->cityRepository = $cityRepository;
    }


    public function index(Request $request)
    {
        $listingsPaginator = $this->listingService->search($request);

        return response()->json([
           "listingsPaginator" => $listingsPaginator
        ], 200);
    }

    public function show($id)
    {
        $listing = Listing::fetchSingle($id)->isFavoritedByUser()->first();
        $listing->makeBreadcrumbs();

        return response()->json([
            "listing" => $listing
        ],200);
    }


    public function create()
    {
        $categories = $this->categoryRepository->getAll();
        $cities = $this->cityRepository->getAll();

        return response()->json([
            "categories" => $categories,
            "cities" => $cities
        ],200);

    }

    public function store(CreateListingRequest $request)
    {
        $category = Category::findOrFail($request["category_id"]);
        $city = City::findOrFail($request["city_id"]);
        $user = auth()->user();

        return $this->listingService->create($request, $user, $city, $category);
    }

    public function edit(Request $request)
    {
        $listing = Listing::findOrFail($request["id"]);
        $categories = $this->categoryRepository->getAll();
        $cities = $this->cityRepository->getAll();

        $listing->load(['category', 'city', 'images']);

        return response()->json([
            "categories" => $categories,
            "cities" => $cities,
            "listing" => $listing
        ],200);
    }

    public function update(UpdateListingRequest $request)
    {
        $category = Category::findOrFail($request["category_id"]);
        $city = City::findOrFail($request["city_id"]);
        $listing = Listing::findOrFail($request["id"]);
        $user = auth()->user();

        if(Gate::allows('update-listing', $listing))
            return $this->listingService->update($request,$listing,$city,$category);
    }

    public function delete(Request $request, Listing $listing)
    {
        $listing = Listing::findOrFail($request["id"]);
        if(Gate::allows('delete-listing', $listing))
            return $this->listingService->delete($listing);
        else
            return response()->json([
                'errors' => [
                    'msg' => "У вас не имеется прав для осуществления этого действия"
                ]
            ], 200);
    }

    public function search(Request $request)
    {
        $listingsPaginator = $this->listingService->search($request);
        return response()->json([
            "listingsPaginator" => $listingsPaginator
        ], 200);
    }

    public function uploadPhoto(UploadPhotoRequest $request)
    {

        $listing = Listing::findOrFail($request["id"]);

        $this->listingService->checkImageCount($listing->id);

        if(Gate::allows('upload-photo', $listing)){
            $listingImage = $this->listingService->uploadPhoto($listing, $request["photo"]);
            return response()->json([
               'msg' => "Фото Добавлено",
                "listingImage" => $listingImage
            ], 200);
        }
    }

    public function removePhoto(Request $request)
    {
        $listing = Listing::findOrFail($request["listing_id"]);
        $listingImage = ListingImage::findOrFail($request["id"]);
        $img_path = $listingImage->getImagePath();

        if(Gate::allows('remove-photo', $listing)){
            return DB::transaction(function() use ($listingImage, $img_path) {
                try{
                    $listingImage->delete();
                    $this->listingService->removePhoto($img_path);
                   return response()->json([
                       "msg" => "Фото успешно удалено"
                   ],200);
                }catch (\Exception $e){
                    DB::rollBack();
                }
            });
        }
    }
}
