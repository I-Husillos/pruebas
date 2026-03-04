<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ProductCategory;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class ProductCategoriesIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/ProductCategories/Index', [
            'apiUrl' => route('api.v1.product-categories.list'),
        ]);
    }
}
