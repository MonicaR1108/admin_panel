<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        // Use Bootstrap 5 pagination views (prevents oversized SVG icons from Tailwind views).
        Paginator::useBootstrapFive();

        // Global UI date helpers (DD/MM/YYYY).
        Blade::directive('dmy', fn ($expression) => "<?php echo \\App\\Support\\Ui::dmy($expression); ?>");
        Blade::directive('timehm', fn ($expression) => "<?php echo \\App\\Support\\Ui::time($expression); ?>");
    }
}
