<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title_en' => 'required|min:2',
            'title_ka' => 'required|min:2',
            'director_en' => 'required',
            'director_ka' => 'required',
            'year' => 'required',
            'genre' => 'required',
            'description_en' => 'required',
            'description_ka' => 'required',
            'thumbnail' => 'required|image'

        ];
    }
}
