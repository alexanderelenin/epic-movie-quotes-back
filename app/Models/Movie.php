<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quote;
use App\Models\User;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
    use HasFactory, HasTranslations;
    public $translatable = ['title', 'director', 'description'];

    protected $fillable = ["title_en", 'title_ka', "director_en", 'director_ka', "genre", "description_en", 'description_ka', "year", "user_id"];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
    public function user()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
