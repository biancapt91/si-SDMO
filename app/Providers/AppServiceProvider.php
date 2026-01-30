<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

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
        // Set Carbon ke bahasa Indonesia
        Carbon::setLocale('id');

        // Blade directive: @tanggal($tgl)
        Blade::directive('tanggal', function ($tanggal) {
            return "<?php echo \\Carbon\\Carbon::parse($tanggal)->translatedFormat('d F Y'); ?>";
        });
    }
}
