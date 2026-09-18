<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedDealer;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('about', [
            'about' => [
                'heading' => 'Technology made for the road ahead.',
                'body' => 'Armour helps Filipino drivers build smarter, safer, and more enjoyable vehicles through dependable car technology, practical accessories, and professional installation. We focus on recommending the right equipment, installing it with care, and supporting every customer through a trusted local network.',
                ...SiteSetting::content('about'),
            ],
        ]);
    }

    public function dealers(): View
    {
        return view('dealers', [
            'dealers' => AuthorizedDealer::published()->get(),
        ]);
    }
}
