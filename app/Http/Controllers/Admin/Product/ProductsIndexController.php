<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class ProductsIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Products/Index', [
            'apiUrl' => route('api.v1.products.list'),
        ]);
    }
}
