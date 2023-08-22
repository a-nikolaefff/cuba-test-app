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
        Schema::create('articles_words', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('word_id');
            $table->unsignedBigInteger('occurrences');
            $table->timestamps();

            $table->foreign('article_id')->references('id')
                ->on('articles')->cascadeOnDelete();
            $table->foreign('word_id')->references('id')
                ->on('words')->cascadeOnDelete();

            $table->index(['article_id', 'word_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles_words');
    }
};
