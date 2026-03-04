<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'products' => \App\Models\Product::count(),
                'markets' => \App\Models\Market::count(),
                'languages' => \App\Models\Language::count(),
                'articles' => \App\Models\ContentArticle::count(),
                'treatments' => \App\Models\Treatment::count(),
                'pages' => \App\Models\Page::count(),
            ],
        ]);
    }
}
