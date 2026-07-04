<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        abort_unless($category->is_active, 404);

        $category->public_image_url = $this->imageUrl($category->icon, 'Category');

        $products = Product::query()
            ->whereBelongsTo($category)
            ->latest()
            ->paginate(12)
            ->through(function (Product $product) {
                $product->public_image_url = $this->imageUrl($product->image, 'Product');

                return $product;
            });

        return view('categories.show', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    private function imageUrl(?string $image, string $fallback): string
    {
        $image = trim((string) $image);

        if ($image === '') {
            return 'https://via.placeholder.com/900x620?text='.rawurlencode($fallback);
        }

        if (Str::startsWith($image, ['http://', 'https://', '//'])) {
            return $image;
        }

        return route('media.image', ['path' => ltrim($image, '/')]);
    }
}
