@extends('layouts.app')

@section('title', $product->name.' | Fiber Optics Kenya')
@section('meta_description', $product->meta_description ?: $product->name.' available from Fiber Optics Kenya.')

@section('content')
<style>
    .product-page { background:#f6f7fb; color:#0f172a; padding:42px 20px 72px; }
    .product-shell { max-width:1180px; margin:0 auto; }
    .product-grid { display:grid; grid-template-columns:minmax(0, 540px) minmax(0, 1fr); gap:38px; align-items:start; }
    .product-media, .product-panel { background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 12px 32px rgba(15,23,42,.06); }
    .product-media { padding:28px; }
    .product-media img { width:100%; aspect-ratio:1 / .82; object-fit:contain; display:block; }
    .product-panel { padding:30px; }
    .product-category { color:#0a4588; font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; margin:0 0 10px; }
    .product-title { font-size:clamp(32px, 4vw, 48px); line-height:1.05; font-weight:850; margin:0 0 18px; color:#071226; }
    .product-price { font-size:28px; line-height:1.2; font-weight:850; color:#0a4588; margin:0 0 18px; }
    .product-stock { color:#475569; margin:0 0 24px; }
    .product-cart { display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:24px; }
    .product-cart input { width:92px; height:48px; border:1px solid #dbe3ea; border-radius:8px; padding:0 12px; font-size:16px; }
    .product-button { min-height:48px; border:0; border-radius:8px; background:#1677ff; color:#fff; font-weight:800; padding:0 20px; }
    .product-back { display:inline-flex; align-items:center; min-height:44px; border-radius:8px; background:#eef2f7; color:#0a4588; font-weight:800; padding:0 16px; text-decoration:none; }
    .product-description { margin-top:28px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:30px; font-size:18px; line-height:1.65; }
    .product-description h2, .product-description h3 { color:#071226; font-weight:850; }
    @media(max-width:900px){ .product-grid{grid-template-columns:1fr;} .product-panel{padding:24px;} }
</style>

<section class="product-page">
    <div class="product-shell">
        <div class="product-grid">
            <figure class="product-media">
                <img src="{{ $product->public_image_url }}" alt="{{ $product->name }}">
            </figure>

            <section class="product-panel">
                <p class="product-category">{{ $product->category?->name ?? 'Product' }}</p>
                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-price">KSh {{ number_format((float) $product->price, 2) }}</p>
                <p class="product-stock">{{ $product->quantity > 0 ? $product->quantity.' available' : 'Contact us for availability' }}</p>

                <form class="product-cart" method="post" action="{{ route('cart.items.store', $product) }}">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" aria-label="Quantity">
                    <button class="product-button" type="submit">Add to cart</button>
                </form>

                <a class="product-back" href="{{ route('home') }}">Continue shopping</a>
            </section>
        </div>

        @if(trim((string) $product->description) !== '')
            <section class="product-description">
                {!! $product->description !!}
            </section>
        @endif
    </div>
</section>
@endsection
