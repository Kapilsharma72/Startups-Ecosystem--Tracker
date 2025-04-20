<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('location');
            $table->string('type');
            $table->json('focus_areas');
            $table->integer('portfolio_size');
            $table->decimal('total_investment', 15, 2);
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->json('team_members')->nullable();
            $table->json('investment_criteria')->nullable();
            $table->json('portfolio_companies')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('investors');
    }
}; 