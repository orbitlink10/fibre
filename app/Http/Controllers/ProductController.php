<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load('category');
        $product->public_image_url = $this->imageUrl($product->image);

        return view('products.show', [
            'product' => $product,
        ]);
    }

    private function imageUrl(?string $image): string
    {
        $image = trim((string) $image);

        if ($image === '') {
            return 'https://via.placeholder.com/900x700?text=Product';
        }

        if (Str::startsWith($image, ['http://', 'https://', '//'])) {
            return $image;
        }

        return route('media.image', ['path' => ltrim($image, '/')]);
    }
}
