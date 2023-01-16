<?php

namespace App\Http\Requests;

use App\Models\Movie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovieUpdateRequest extends FormRequest
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
    public function rules(Movie $movie)
    {
        return [
            'title_en' => 'required|min:3',
            'title_ka' => 'required|min:3',
            'director_en' => 'required|min:3',
            'director_ka' => 'required|min:3',
            'description_en' => 'required',
            'description_ka' => 'required',
            'year' => 'required',
            'genre' => 'required',
            'thumbnail' => 'nullable|image'


        ];
    }
}
