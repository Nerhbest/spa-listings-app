<?php
namespace App\Filters\Listing\Transformers;

use App\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryTransformer
{
    public function apply(Builder $builder, Request $request){
        $category = Category::find($request['category']);
        $ids = $category->childsIds();
        $builder->whereIn('category_id', $ids);
    }
}