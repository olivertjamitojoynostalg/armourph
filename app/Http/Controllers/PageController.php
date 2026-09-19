<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedDealer;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function about(): View
    {
        $about = [
            'heading' => 'Technology made for the road ahead.',
            'body' => 'Armour helps Filipino drivers build smarter, safer, and more enjoyable vehicles through dependable car technology, practical accessories, and professional installation. We focus on recommending the right equipment, installing it with care, and supporting every customer through a trusted local network.',
            'reviews_heading' => 'What our customers say',
            'review_images' => [],
            ...SiteSetting::content('about'),
        ];

        $about['review_images'] = collect($about['review_images'])
            ->filter()
            ->map(fn (string $path): string => Str::startsWith($path, ['assets/', 'storage/'])
                ? asset($path)
                : Storage::disk('public')->url($path))
            ->values()
            ->all();

        return view('about', [
            'about' => $about,
        ]);
    }

    public function dealers(): View
    {
        return view('dealers', [
            'dealers' => AuthorizedDealer::published()->get(),
        ]);
    }
}
