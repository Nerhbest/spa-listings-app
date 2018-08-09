<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->makeCategories();

    }

    public function makeCategories()
    {
        $categoriesText = file_get_contents(base_path("fakedata/categories.json"));
        $categories = json_decode($categoriesText, true);
        foreach($categories["categories"] as $category)
        {
            $category["slug"] = str_slug($category["name"]);
            foreach($category["children"] as &$child){
                $child["slug"] = str_slug($child["name"]);
            }

            Category::create($category);
        }



    }
}
