<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $table = "categories";
    public $timestamps = false;
    protected $guarded = [""];
    protected $hidden = ["_lft", "_rgt"];

    /**
    * Methods
    */
    public function getAllHierarchy()
    {
        $parent = $this->parent ? $this->parent->getAllHierarchy() : [];
        $own = [
            "name" => $this->name,
            "slug" => $this->slug
        ];
        array_push($parent, $own);
        return $parent;
    }
    
    /**
    * Methods
    */
    public function cachedAll()
    {
        return Cache::rememberForever('cached_categories', function() {

        });
    }

    /**
    * Relations
    */
    public function listings()
    {
        return $this->hasMany(Listing::class,  "category_id");
    }

    public function getRouteKeyName()
    {
        return "slug";
    }
}
