<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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
        RateLimiter::for('inquiries', function (Request $request): array {
            $visitorKey = hash('sha256', $request->ip() ?: 'unknown');

            return [
                Limit::perMinute(3)->by('inquiry-minute:'.$visitorKey),
                Limit::perDay(20)->by('inquiry-day:'.$visitorKey),
            ];
        });

        View::composer('partials.inquiry-assistant', function ($view): void {
            $view->with([
                'inquiryBranches' => Branch::published()->get(['id', 'name', 'address', 'latitude', 'longitude']),
                'inquiryFormToken' => Crypt::encryptString((string) now()->timestamp),
            ]);
        });

        View::composer('partials.footer', function ($view): void {
            $view->with('footerStores', SiteSetting::content('stores'));
        });

        View::composer('partials.catalog-cta', function ($view): void {
            $view->with('detailStores', SiteSetting::content('stores'));
        });
    }
}
