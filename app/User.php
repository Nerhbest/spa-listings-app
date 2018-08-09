<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',"phone_number"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at'
    ];

    /**
    * Relations
    */
    public function listings()
    {
        return $this->hasMany(Listing::class, "user_id");
    }

    /**
    * Scopes
    */
    public function scopeAlreadyExists($query, $email, $phone) {
        return $query->where("email", $email)->orWhere("phone_number", $phone);
    }

    public function scopeAccountInfoById($query, $id)
    {
    }


    /**
    * Methods
    */
    public function resolveErrors($request)
    {
        $errors = [];

        $input = $request->only(["phone_number", "email"]);

        foreach($input as $key => $value){
            if($this[$key] == $value){
                $errors[$key] = "Пользователь с такими данными уже существует";
            }
        }

        return [
            "errors" => $errors
        ];
    }

    public function favoriteListings()
    {
        return $this->belongsToMany(Listing::class, 'favorites_listings', 'user_id', 'listing_id');
    }

    /**
    * Jwt Interface
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
