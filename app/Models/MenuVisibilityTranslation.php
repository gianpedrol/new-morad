<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuVisibilityTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_visibility_id',
        'language_code',
        'custom_name',
    ];

    public function menuVisibility(){
        return $this->belongsTo(MenuVisibility::class, 'menu_visibility_id');
    }
}
