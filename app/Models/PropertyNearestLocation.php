<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyNearestLocation extends Model
{
    use HasFactory;

    public function nearestLocation(){
        return $this->belongsTo(NearestLocation::class);
    }
}
