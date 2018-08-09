<?php

namespace App\Http\Requests;

use App\Rules\ImageCount;
use Illuminate\Foundation\Http\FormRequest;

class CreateListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required|min:3|max:255",
            "body" => "required|min:3|max:2000",
            "price" => "required|numeric",
            "place" => "required",
            "lat" => "required|regex:#\d{2,4}\.\d{3,7}#",
            "lng" => "required|regex:#\d{2,4}\.\d{3,7}#",
            "photos" => [ new ImageCount()],
            'photos.*' => ['mimes:jpg,jpeg,png', 'max:3000']
        ];
    }
}