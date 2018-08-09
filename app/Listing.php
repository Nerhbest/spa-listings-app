<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

class Listing extends Model
{
    use Searchable;

    protected $table = "listings";
    protected $guarded = [""];
    protected $hidden = ["updated_at"];
    protected $appends = ['main_photo'];

    /**
    * Relations
    */
    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class, "listing_id");
    }
    /**
    * Scopes
    */
    /**
     * @param $query
     * @param City $city
     * @param Category $category
     * @return mixed
     */
    public function scopeSetCityAndCategoryParams($query,City $city, Category $category)
    {
        $subCities = $city->descendants()->pluck('id')->toArray();

        return $query->select("*")
                     ->where("category_id", $category->id)
                     ->where("live", true)
                     ->whereIn("city_id", count($subCities) > 0 ?
                                              array_merge([$city->id], [$city->descendants()->pluck('id')->toArray()] ) :
                                              [$city->id]
                     );
    }


    public function scopeIsFavoritedByUser($query)
    {
        if(auth()->check()){
            $query->select("*");
            return $query->selectRaw("(select count(user_id) from favorites_listings where user_id = ? and listing_id = listings.id) as is_favorite", [auth()->id()]);
        }
        else
            return $query;
    }

    public function scopeFetchSingle($query, $id)
    {

        return $query->with(['category', 'owner', 'images'])->where('id', $id);
    }

    public function makeBreadcrumbs()
    {
        if(!$this->category) throw new \DomainException("Category on that model does not exist");

        $this->setAttribute("breadcrumbs", $this->category->getAllHierarchy());
    }
    /**
    * Accessors
    */

    public function getMainPhotoAttribute()
    {
            if(count($this->images) == 0)
                return asset("storage/listings/default.jpg");
            else
                return $this->images[0]->img_path;
    }

    public function getCreatedAtAttribute($key) {
        return Carbon::createFromFormat('Y-m-d H:i:s', $key)->diffForHumans();
    }

    /**
    * Algolia
    */
    public function searchableAs()
    {
        return "listings";
    }

    public function toSearchableArray()
    {
        $listing = $this->only(["title", "body"]);
        $listing['category_id'] = $this->category->id;
        $listing['city_id'] = $this->city->id;

        return $listing;
    }
}
