<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ProductCategory;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Inertia\Inertia;
use Inertia\Response;

final class ProductCategoryEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $category = ProductCategory::findOrFail($id);

        return Inertia::render('Admin/ProductCategories/Edit', [
            'category' => $category,
        ]);
    }
}
