<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class MarketsIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Markets/Index', [
            'apiUrl' => route('api.v1.markets.list'),
        ]);
    }
}
