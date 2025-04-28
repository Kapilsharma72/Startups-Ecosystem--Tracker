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
            $table->integer('founded')->nullable()->after('description');
            $table->decimal('avg_check', 10, 2)->nullable()->after('total_investment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn('founded');
            $table->dropColumn('avg_check');
        });
    }
};