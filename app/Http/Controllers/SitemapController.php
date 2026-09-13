<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', [
                'products' => Product::published()->get(['slug', 'updated_at']),
                'packages' => Package::published()->get(['slug', 'updated_at']),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
