<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('startups', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn('metrics');
            $table->dropColumn('team_members');
            
            // Add new column
            $table->integer('emp_numbers')->nullable()->after('website');
        });
    }

    public function down()
    {
        Schema::table('startups', function (Blueprint $table) {
            // Drop the new column
            $table->dropColumn('emp_numbers');
            
            // Add back the old columns
            $table->json('team_members')->nullable()->after('website');
            $table->json('metrics')->nullable()->after('technologies');
        });
    }
};