<?php

namespace App\Repositories;

use App\Category;
use App\Listing;
use App\User;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListingRepository  extends Repository
{

    public function loadListingsForUser(User $user)
    {
        return $user->listings()->with("images")->latest()->paginate(6);
    }

    public function loadFavoriteListingsForUser(User $user)
    {
        return $user->favoriteListings()->with("images")->latest()->paginate(6);
    }


    public function configureListingBuilderBeforePaginate(Builder $builder)
    {
        $builder->select("*");
        if(auth()->check())
            $builder->IsFavoritedByUser(auth()->id());
        $builder->with('images');
        $builder->latest();
    }

    public function searchListingsInAlgolia(Request $request)
    {
        $builder = $this->model->search($request["searchTerm"]);

        if($request->has("city"))
            $builder->where('city_id', $request["city"]);
        if($request->has('category')){
            $builder->where('category_id', $request['category']);
        }

        $listingsPaginator = $builder->paginate(6);

        return $listingsPaginator;
    }
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Listing::class;
    }
}