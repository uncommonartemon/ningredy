<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            //$table->json('name'); 
            $table->string('name_en');
            $table->string('name_cz');
            $table->string('name_ru');
            $table->string('image')->nullable(); 
            $table->string('image_light')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
