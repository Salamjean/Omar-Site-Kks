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
        Schema::create('commandes', function (Blueprint $table) {
             $table->id();
             $table->string('article_name'); 
             $table->decimal('unit_price');
             $table->string('main_image'); 
             $table->string('categorie');
             $table->integer('quantity');
             $table->decimal('total_price');
             $table->string('status')->default('En attente');
             $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
             $table->foreignId('user_id')->constrained()->onDelete('cascade');

             //Les informations de livraisons 
             $table->string('name_destinataire')->nullable();
             $table->string('contact_destinataire')->nullable();
             $table->string('ville')->nullable();
             $table->string('commune')->nullable();
             $table->string('quartier')->nullable();
             $table->string('code_postal')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
