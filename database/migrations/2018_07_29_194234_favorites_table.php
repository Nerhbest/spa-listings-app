<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites_listings', function (Blueprint $table) {
            $table->unsignedInteger("user_id");
            $table->unsignedInteger("listing_id");

            $table->foreign("user_id")->references('id')->on("users")->onDelete("CASCADE");
            $table->foreign("listing_id")->references('id')->on('listings')->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}