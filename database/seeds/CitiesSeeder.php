<?php

use Illuminate\Database\Seeder;
use App\City;


class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->makeCities() as $city)
        {
            $coords = $this->getCoordsForCity($city["name"]);
            $city["lat"] = $coords["lat"];
            $city["lng"] =$coords["lng"];
            $city["slug"] = str_slug($city["name"]);

            if(array_key_exists("children", $city)){
                foreach($city["children"] as &$subCity)
                {
                    $coordsSub = $this->getCoordsForCity($subCity["name"]);
                    $subCity["lat"] = $coordsSub["lat"];
                    $subCity["lng"] =$coordsSub["lng"];
                    $subCity["slug"] = str_slug($subCity["name"]);
                }
                City::create($city);
            }else{
                City::create($city);
            }

        }
    }

    public function getCoordsForCity(string $city)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request("GET",  "https://geocode-maps.yandex.ru/1.x/?geocode={$city}&format=json");

        $respContent = $res->getBody()->getContents();
        $json = json_decode($respContent, true);
        $pointString = $json["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["Point"]["pos"];
        $point = explode(" " , $pointString);
        $coords = [
            "lat" => $point[1],
            "lng" => $point[0]
        ];
        return $coords;
    }

    public function makeCities(){
        return [
            [
                "name" => "Москва"
            ],
            [
                "name" => "Санкт-Петербург",
            ],
            [
                "name" => "Астрахань"
            ],
            [
                "name" => "Барнаул"
            ],
            [
                "name" => "Волгоград"
            ],
            [
                "name" => "Воронеж"
            ],
            [
                "name" => "Екатеринбург"
            ],
            [
                "name" => "Ижевск"
            ],
            [
                "name" => "Казань"
            ],
            [
                "name" => "Калининград"
            ],
            [
                "name" => "Краснодар"
            ],
            [
                "name" => "Красноярск"
            ],
            [
                "name" => "Набереные челны"
            ],
            [
                "name" => "Нижний новгород"
            ],
            [
                "name" => "Новосибирск"
            ],
            [
                "name" => "Омск"
            ],
            [
                "name" => "Оренбург"
            ],
            [
                "name" => "Пермь"
            ],
            [
                "name" => "Ростов-на-Дону"
            ],
            [
                "name" => "Самара"
            ],
            [
                "name" => "Саратов"
            ],
            [
                "name" => "Ставрополь"
            ],
            [
                "name" => "Толятти"
            ],
            [
                "name" => "Тула"
            ],
            [
                "name" => "Тюмень"
            ],
            [
                "name" => "Ульяновск"
            ],
            [
                "name" => "Уфа"
            ],
            [
                "name" => "Челябинск"
            ],
            [
                "name" => "Ярославль"
            ]
        ];
    }
}
