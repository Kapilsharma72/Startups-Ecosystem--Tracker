<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Fix for JSON column handling in MySQL
        \DB::listen(function($query) {
            if (str_contains($query->sql, 'team_members') || 
                str_contains($query->sql, 'technologies') || 
                str_contains($query->sql, 'metrics')) {
                foreach ($query->bindings as $i => $binding) {
                    if (is_array($binding)) {
                        $query->bindings[$i] = json_encode($binding);
                    }
                }
            }
        });
        
        // Add a macro for handling JSON fields properly
        Builder::macro('withJson', function($column, $value) {
            $value = is_array($value) ? json_encode($value) : $value;
            return $this->whereRaw("JSON_CONTAINS({$column}, ?)", [$value]);
        });
    }
}
