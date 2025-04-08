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
        Schema::create('article_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->text('description');
            $table->string('nombre')->default(0);
            $table->string('categorie');
            $table->string('typeAccessoire')->nullable();
            $table->string('other')->nullable();
            $table->string('main_image');
            $table->string('hover_image');
            $table->string('total');
            $table->string('reduced');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_fournisseurs');
    }
};
