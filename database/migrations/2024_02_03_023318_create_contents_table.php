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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->string('title');
            $table->string('description');
            $table->string('image');
            $table->string('type');
            $table->boolean('active');
            $table->bigInteger('order');
            $table->string('description_id');
            $table->string('title_id');
            $table->string('description_en');
            $table->string('title_en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
