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
        Schema::table('investors', function (Blueprint $table) {
            // Change array fields to text
            $table->text('focus_areas')->change();
            $table->text('team_members')->change();
            $table->text('investment_criteria')->change();
            $table->text('portfolio_companies')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            // Change back to json if needed
            $table->json('focus_areas')->change();
            $table->json('team_members')->change();
            $table->json('investment_criteria')->change();
            $table->json('portfolio_companies')->change();
        });
    }
};