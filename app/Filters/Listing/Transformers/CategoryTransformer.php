<?php
namespace App\Filters\Listing\Transformers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryTransformer
{
    public function apply(Builder $builder, Request $request)
    {
        $builder->where('category_id', $request["category"]);
    }
}