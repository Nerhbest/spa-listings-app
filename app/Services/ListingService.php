<?php
namespace App\Services;

use App\Category;
use App\City;
use App\Filters\Listing\ListingFilter;
use App\Listing;
use App\ListingImage;
use App\Repositories\ListingRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ListingService
{
    private $imageUploadService;
    private $listingFilter;
    private $listingRepository;

    /**
     * @param ImageUploadService $imageUploadService
     * @param ListingFilter $listingFilter
     * @param ListingRepository $listingRepository
     */
    function __construct(ImageUploadService $imageUploadService, ListingFilter $listingFilter, ListingRepository $listingRepository)
    {
        $this->imageUploadService = $imageUploadService;
        $this->listingFilter = $listingFilter;
        $this->listingRepository = $listingRepository;
    }

    /**
     * @param Request $request
     * @param User $user
     * @param City $city
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, User $user, City $city, Category $category)
    {
        $listing = Listing::make([
            "title" => $request["title"],
            "body" => $request["body"],
            "price" => $request["price"],
            "place" => $request["place"],
            "lat" => $request["lat"],
            "lng" => $request["lng"],
        ]);

        $listing->owner()->associate($user);
         $listing->city()->associate($city);
        $listing->category()->associate($category);

        DB::beginTransaction();

        try {
            $listing->save();


            if($this->needUploadPhotos($request)){
                foreach ($request["photos"] as $image){
                    $savedImagePath = $this->imageUploadService->saveImage($image);
                    ListingImage::create([
                        "listing_id" => $listing->id,
                        "img_path" => "listings/{$savedImagePath}"
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                "msg" => 'Объявление    успешно создано'
            ]);
        }catch (\Exception $e){
            DB::rollBack();
        }

    }

    public function needUploadPhotos(Request $request)
    {
        return !empty($request["photos"]) ? true : false;
    }


    public function update(Request $request, Listing $listing,  City $city, Category $category)
    {
        $listing->fill([
            "title" => $request["title"],
            "body" => $request["body"],
            "price" => $request["price"],
            "place" => $request["place"],
            "lat" => $request["lat"],
            "lng" => $request["lng"],
        ]);

        $listing->city()->associate($city);
        $listing->category()->associate($category);

        $listing->save();

        return response()->json([
           "msg" => "Объявление обновлено" ,
           "listing" => $listing
        ], 200);
    }

    public function delete(Listing $listing)
    {
        DB::beginTransaction();
        try{

            $images = $listing->images;
            $listing->delete();
            foreach($images as $image) {
                $imagePath = $image->getImagePath();
                $image->delete();
                $this->removePhoto($imagePath);
            }

            DB::commit();

            return response()->json([
                "msg" => "Объявление успешно удалено"
            ],200);

        }catch (Exception $e)
        {
            DB::rollBack();
            return response()->json([
                "errors" => [
                    'msg' => "Не удалось удалить объявление,внутренняя ошибка сервера.Специалисты Уже работают над устранением"
                ]
            ]);
        }

    }

    public function search(Request $request)
    {
        if($this->needUseAlgolia($request)){
            return $this->listingRepository->searchListingsInAlgolia($request);
        }else {
            $builder = $this->listingFilter->apply($request);
            $this->listingRepository->configureListingBuilderBeforePaginate($builder);
            $listingsPaginator = $builder->paginate(6);
            return $listingsPaginator;
        }
    }

    public function needUseAlgolia(Request $request)
    {
        return $request->has('searchTerm') ? true : false;
    }

    public function uploadPhoto($listing,$image)
    {
        $savedImagePath = $this->imageUploadService->saveImage($image);
        $listingImage = ListingImage::create([
            "listing_id" => $listing->id,
            "img_path" => "listings/{$savedImagePath}"
        ]);
        return $listingImage;
    }

    public function removePhoto(string $path)
    {
        return $this->imageUploadService->removePhoto($path);
    }

    public function checkImageCount($listingId)
    {
        $imageCnt = ListingImage::where('listing_id', $listingId)->count();
        if($imageCnt >= ListingImage::MAX_COUNT) throw new \DomainException(sprintf("Объявление не может содержать более %s фотографий",
            ListingImage::MAX_COUNT));

        return true;
    }

}