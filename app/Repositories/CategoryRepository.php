<?php
/**
 * Created by PhpStorm.
 * User: nerh
 * Date: 09.08.18
 * Time: 19:48
 */

namespace App\Repositories;

use App\Category;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends Repository
{
    public function getAll()
    {
        return Cache::rememberForever('categories', function() {
            return Category::get()->toTree();
        });
    }



    public function model()
    {
        return Category::class;
    }
}