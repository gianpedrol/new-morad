<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            $table->string('top_property_title')->nullable();
            $table->text('top_property_description')->nullable();
            $table->integer('top_property_item')->nullable();

            $table->string('featured_property_title')->nullable();
            $table->text('featured_property_description')->nullable();
            $table->integer('featured_property_item')->nullable();

            $table->string('urgent_property_title')->nullable();
            $table->text('urgent_property_description')->nullable();
            $table->integer('urgent_property_item')->nullable();

            $table->string('service_title')->nullable();
            $table->text('service_description')->nullable();
            $table->integer('service_item')->nullable();

            $table->string('agent_title')->nullable();
            $table->text('agent_description')->nullable();
            $table->integer('agent_item')->nullable();

            $table->string('blog_title')->nullable();
            $table->text('blog_description')->nullable();
            $table->integer('blog_item')->nullable();

            $table->string('testimonial_title')->nullable();
            $table->text('testimonial_description')->nullable();
            $table->integer('testimonial_item')->nullable();










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
        Schema::dropIfExists('home_pages');
    }
}
