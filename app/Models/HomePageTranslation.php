<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_page_id',
        'language_code',
        'top_property_title',
        'top_property_description',
        'featured_property_title',
        'featured_property_description',
        'urgent_property_title',
        'urgent_property_description',
        'service_title',
        'service_description',
        'agent_title',
        'agent_description',
        'blog_title',
        'blog_description',
        'testimonial_title',
        'testimonial_description',
    ];
}
