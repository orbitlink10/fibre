<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function show()
    {
        return view('cart.show', [
            'items' => $this->cartItems(),
            'total' => $this->cartItems()->sum('line_total'),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $id = (string) $product->id;

        $cart[$id] = ($cart[$id] ?? 0) + $quantity;

        session(['cart' => $cart]);

        return back()->with('success', $product->name.' added to cart.');
    }

    public function update(Request $request, Product $product)
    {
        $quantity = max(0, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $id = (string) $product->id;

        if ($quantity === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $quantity;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[(string) $product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Product removed from cart.');
    }

    private function cartItems()
    {
        $cart = collect(session('cart', []))
            ->mapWithKeys(fn ($quantity, $id) => [(int) $id => max(1, (int) $quantity)])
            ->filter();

        if ($cart->isEmpty()) {
            return collect();
        }

        return Product::query()
            ->with('category')
            ->whereIn('id', $cart->keys())
            ->get()
            ->map(function (Product $product) use ($cart) {
                $quantity = $cart->get($product->id, 1);
                $product->cart_quantity = $quantity;
                $product->cart_image_url = $this->imageUrl($product->image);
                $product->line_total = (float) $product->price * $quantity;

                return $product;
            });
    }

    private function imageUrl(?string $image): string
    {
        $image = trim((string) $image);

        if ($image === '') {
            return 'https://via.placeholder.com/320x240?text=Product';
        }

        if (Str::startsWith($image, ['http://', 'https://', '//'])) {
            return $image;
        }

        return route('media.image', ['path' => ltrim($image, '/')]);
    }
}
