<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyReview extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class)->select('name','id','email','image');
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->select('id','title','slug','thumbnail_image');
    }
}
