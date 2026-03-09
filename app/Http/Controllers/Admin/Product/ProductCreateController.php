<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;
use App\Models\ProductCategory;
use App\Models\Market;
use App\Models\Form;

final class ProductCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Products/Create', [
            'categories' => ProductCategory::all(['id', 'name']),
            'markets'    => Market::where('active', true)->get(['id', 'code', 'name']),
            'forms'      => Form::all(['id', 'name']),
        ]);
    }
}
