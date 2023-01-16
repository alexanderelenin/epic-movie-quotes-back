<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;
use App\Models\User;
use App\Models\Comment;
use Spatie\Translatable\HasTranslations;


class Quote extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['quote'];


    protected $fillable = ['quote_en', 'quote_ka', 'thumbnail', 'movie_id', 'user_id'];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'quote_id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'quote_id');
    }
}
