<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->unsignedInteger("city_id");
            $table->unsignedInteger("category_id");
            $table->string("title");
            $table->text("body");
            $table->unsignedInteger("price");
            $table->string("place");
            $table->decimal("lat",  10, 6);
            $table->decimal("lng", 10, 6);
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->onDelete('CASCADE');
            $table->foreign("city_id")->references("id")->on("cities")->onDelete("CASCADE");
            $table->foreign("category_id")->references("id")->on("categories")->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
