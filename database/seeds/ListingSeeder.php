<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\City;
class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all()->toArray();
        $cities = City::all()->toArray();
        $users = \App\User::all()->toArray();

        $categoryMaxId = count($categories) - 1;
        $citiesMaxId = count($cities) - 1;
        $usersMaxId = count($users) - 1;

        for($i = 0; $i < 150; $i++){
            $city = $cities[rand(0, $citiesMaxId)];
            $listing = factory(\App\Listing::class, 1)->make([
                "category_id" => $categories[rand(0, $categoryMaxId)]['id'],
                "city_id" => $city['id'],
                "place" => $city['name'],
                "lat" => $city['lat'],
                "lng" => $city['lng'],
                "user_id" => $users[rand(0, $usersMaxId)]["id"]
            ]);

            $listing[0]->save();
        }






    }
}
