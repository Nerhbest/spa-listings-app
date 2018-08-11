<?php
/**
* Listing
*/
Route::get("listing/create", "ListingController@create");
Route::get("listing", "ListingController@index");
Route::get("listing/search", "ListingController@search");
Route::post("listing/update", "ListingController@update");
Route::post("listing/{id}/delete", "ListingController@delete");
Route::post("listing/photo/remove", "ListingController@removePhoto");
Route::post("listing/photo/upload", "ListingController@uploadPhoto");
Route::get("listing/edit/{id}", "ListingController@edit");
Route::get("listing/{id}", "ListingController@show");
Route::get("listing/{city}/{category}", "ListingController@index");
Route::post("listing/store", "ListingController@store");
/**
* Auth
*/
Route::post("login", "AuthController@login");
Route::post("register", "AuthController@register");
Route::post("logout", "AuthController@logout");

/**
* Account
*/
Route::get("account-info", "AccountController@info");
Route::get("/account/listing", "AccountController@getUserListings");
Route::get("/account/favorite-listing", "AccountController@getUserFavorites");
Route::post("/account/update-credentials", "AccountController@updateCredentials");
Route::post("/account/update-password", "AccountController@changePassword");
/**
* Favorite
*/
Route::post("listing/favorite/{listing}", "FavoriteController@toggle");

/**
* Main Page
*/
Route::get("main-page", "MainController@index");