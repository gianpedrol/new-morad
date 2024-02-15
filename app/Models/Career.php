<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;



    protected $appends = ['totalApplication'];

    public function getTotalApplicationAttribute()
    {
        return $this->totalRequest()->count();
    }


    public function totalRequest(){
        return $this->hasMany(CareerRequest::class);
    }
}
