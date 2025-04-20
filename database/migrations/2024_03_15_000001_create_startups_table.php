<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('startups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('location');
            $table->string('industry');
            $table->string('stage');
            $table->decimal('funding', 12, 2);
            $table->string('funding_round');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->json('team_members')->nullable();
            $table->json('technologies')->nullable();
            $table->json('metrics')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('startups');
    }
}; 