<?php
namespace App\Filters\Listing;

use App\Filters\Contracts\FilterContract;
use App\Filters\Listing\Transformers\CategoryTransformer;
use App\Filters\Listing\Transformers\CityTransformer;
use App\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListingFilter implements FilterContract
{
    protected $transformersMap  = [
        'city' => CityTransformer::class,
        'category' => CategoryTransformer::class
    ];






    public function apply(Request $request): Builder
    {
        $modelName =  $this->model();
        $listingModel = new $modelName();
        $listingBuilder = $listingModel->query();

        foreach($request->all() as $param => $value){
            if(array_key_exists($param, $this->transformersMap)){
                  $transformer = new $this->transformersMap[$param];
                  $transformer->apply($listingBuilder, $request);
            }

            continue;
        }

        return $listingBuilder;
    }


    public function model():string
    {
        return Listing::class;
    }
}