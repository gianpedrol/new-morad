<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('home_page_id');
            $table->string('language_code');
            $table->string('top_property_title')->nullable();
            $table->text('top_property_description')->nullable();
            $table->string('featured_property_title')->nullable();
            $table->text('featured_property_description')->nullable();
            $table->string('urgent_property_title')->nullable();
            $table->text('urgent_property_description')->nullable();
            $table->string('service_title')->nullable();
            $table->text('service_description')->nullable();
            $table->string('agent_title')->nullable();
            $table->text('agent_description')->nullable();
            $table->string('blog_title')->nullable();
            $table->text('blog_description')->nullable();
            $table->string('testimonial_title')->nullable();
            $table->text('testimonial_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_page_translations');
    }
}
