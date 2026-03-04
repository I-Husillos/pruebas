<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class ProductEditController extends BaseController
{
    public function __invoke(string $id): Response
    {
        return $this->render('Admin/Products/Edit', [
            'productId' => $id,
            'apiUrl' => route('api.v1.products.update', $id),
            'categoriesUrl' => route('api.v1.product-categories.list'),
            'formsUrl' => route('api.v1.forms.list'),
            'marketsUrl' => route('api.v1.markets.list'),
        ]);
    }
}
