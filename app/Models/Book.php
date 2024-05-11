<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Review;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
    ];

public function authers()
    {
        return $this->belongsToMany(Auther::class)->withPivot(['available']);
    }
    public function reviews():MorphMany{
        return $this->morphMany(Review::class,'reviwable' );
    }
    

}
