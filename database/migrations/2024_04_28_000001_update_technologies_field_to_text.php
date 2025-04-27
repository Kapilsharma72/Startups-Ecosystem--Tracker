<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First convert any existing JSON technologies to strings
        $startups = DB::table('startups')->get();
        foreach ($startups as $startup) {
            if ($startup->technologies) {
                try {
                    $technologies = json_decode($startup->technologies, true);
                    if (is_array($technologies)) {
                        $techString = implode(', ', $technologies);
                        DB::table('startups')
                            ->where('id', $startup->id)
                            ->update(['technologies' => $techString]);
                    }
                } catch (\Exception $e) {
                    // Skip if not valid JSON
                }
            }
        }
        
        // Now change the column type
        Schema::table('startups', function (Blueprint $table) {
            // Drop the existing column
            $table->dropColumn('technologies');
        });
        
        Schema::table('startups', function (Blueprint $table) {
            // Re-add as text
            $table->text('technologies')->nullable()->after('team_members');
        });
    }

    public function down()
    {
        // Revert back to JSON
        Schema::table('startups', function (Blueprint $table) {
            $table->dropColumn('technologies');
        });
        
        Schema::table('startups', function (Blueprint $table) {
            $table->json('technologies')->nullable()->after('team_members');
        });
    }
};