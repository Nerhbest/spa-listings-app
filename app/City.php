<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class City extends Model
{
    use NodeTrait;

    protected $table = "cities";
    public $timestamps = false;
    protected $guarded = [""];

    public function getRouteKeyName()
    {
        return "slug";
    }

}
