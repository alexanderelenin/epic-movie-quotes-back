<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'from', 'to', 'read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function receiver()
    {
        return  $this->belongsTo(User::class, 'to');
    }
}
