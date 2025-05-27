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
        Schema::create('category_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable();
            $table->text('name');
            $table->text('url')->nullable();
            $table->enum('target',['_blank','_self']);
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_menus');
    }
};
