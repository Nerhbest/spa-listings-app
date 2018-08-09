<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingImage extends Model
{
    const  MAX_COUNT = 3;


    public $timestamps = false;
    protected $table = "listing_images";
    protected $guarded = [""];




    public function getImgPathAttribute($key)
    {

        return asset("storage/{$key}");
    }


    public function getImagePath()
    {
        return public_path("storage/{$this->getOriginal('img_path')}");
    }







}
