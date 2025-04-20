<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('startup_news_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['startup_id', 'news_article_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('startup_news_article');
    }
}; 