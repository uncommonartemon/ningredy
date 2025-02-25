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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Локализованное название
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // Категория
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->string('country')->nullable();
            $table->string('slug')->nullable();
            $table->integer('price');
            $table->integer('discount')->nullable();// Скидка
            $table->integer('discount_lifespan')->nullable();
            $table->integer('views')->nullable();
            $table->integer('views_uniq')->nullable();
            $table->boolean('used')->default(false); // Б/у
            $table->boolean('active')->default(false); 
            $table->json('attributes')->nullable();
            $table->json('description')->nullable(); // Локализованное описание
            $table->json('images')->nullable(); // Массив ссылок на изображения
            $table->json('tags')->nullable();
            $table->json('info')->nullable();
            $table->json('related_products')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
