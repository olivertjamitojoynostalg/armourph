<?php

use App\Http\Controllers\Admin\CatalogSyncController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PackageCatalogController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\LimitInquiryPayload;
use App\Http\Middleware\RequireAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/products', ProductCatalogController::class)->name('products.index');
Route::get('/products/{product:slug}', [HomeController::class, 'showProduct'])->name('products.show');
Route::get('/packages', PackageCatalogController::class)->name('packages.index');
Route::get('/packages/{package:slug}', [HomeController::class, 'showPackage'])->name('packages.show');
Route::post('/inquiries', InquiryController::class)->middleware([LimitInquiryPayload::class, 'throttle:inquiries'])->name('inquiries.store');
Route::redirect('/login', '/admin/login')->name('login');
Route::post('/admin/sync', CatalogSyncController::class)->middleware(['auth', RequireAdmin::class, 'throttle:5,1'])->name('admin.sync');
