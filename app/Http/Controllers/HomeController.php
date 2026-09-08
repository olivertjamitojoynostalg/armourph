<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'packages' => config('armour.packages'),
            'products' => config('armour.products'),
            'branches' => config('armour.branches'),
            'stores' => config('armour.stores'),
        ]);
    }
}
