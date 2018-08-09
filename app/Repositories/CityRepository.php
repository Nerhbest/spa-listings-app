<?php
namespace App\Repositories;

use App\City;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\Cache;

class CityRepository extends Repository
{
    public function getAll()
    {
        return Cache::rememberForever("cities", function() {
            return City::get()->toTree();
        });
    }



    public function model()
    {
        return City::class;
    }


}