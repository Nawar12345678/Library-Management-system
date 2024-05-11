<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auther extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',

    ];

    public function books()
    {
        return $this->belongsToMany(Book::class)->withPivot(['available']);;
    }
    
    public function reviews():MorphMany{
        return $this->morphMany(Review::class,'reviwable' );
    }
    
}
