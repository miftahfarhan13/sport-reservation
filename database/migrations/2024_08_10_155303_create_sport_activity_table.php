<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sport_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('sport_category_id')->references('id')->on('sport_categories');
            $table->integer('city_id')->references('city_id')->on('cities');
            $table->integer('user_id')->references('id')->on('users');
            $table->text('title');
            $table->text('description');
            $table->text('image_url')->nullable();
            $table->integer('price');
            $table->integer('price_discount')->nullable();
            $table->integer('slot');
            $table->text('address');
            $table->text('map_url');
            $table->date('activity_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sport_activities');
    }
};
