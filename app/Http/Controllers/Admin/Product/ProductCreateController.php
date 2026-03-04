<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class ProductCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Products/Create', [
            'apiUrl' => route('api.v1.products.store'),
            'categoriesUrl' => route('api.v1.product-categories.list'),
            'formsUrl' => route('api.v1.forms.list'),
            'marketsUrl' => route('api.v1.markets.list'),
        ]);
    }
}
