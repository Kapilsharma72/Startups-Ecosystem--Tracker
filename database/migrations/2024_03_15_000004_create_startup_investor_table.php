<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('startup_investor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained()->onDelete('cascade');
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->decimal('investment_amount', 15, 2);
            $table->date('investment_date');
            $table->decimal('equity_stake', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['startup_id', 'investor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('startup_investor');
    }
}; 