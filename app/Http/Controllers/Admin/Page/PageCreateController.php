<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

final class PageCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Pages/Create', [
            'markets' => DB::table('markets')->get(),
            'languages' => DB::table('languages')->get(),
            'forms' => DB::table('forms')->select('id', 'name', 'key')->get(),
        ]);
    }
}
