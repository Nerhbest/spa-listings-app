<?php

namespace App\Filters\Listing\Transformers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CityTransformer
{

    public function apply(Builder $builder, Request $request)
    {
        $builder->where('city_id', $request["city"]);
    }
}