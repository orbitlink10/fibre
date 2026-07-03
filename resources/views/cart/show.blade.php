@extends('layouts.app')

@section('title', 'Cart | Fiber Optics Kenya')
@section('meta_description', 'Review selected fiber optic products before requesting checkout support.')

@section('content')
<style>
    .cart-page { background:#f6f7fb; min-height:60vh; padding:42px 20px 72px; color:#0f172a; }
    .cart-shell { max-width:1120px; margin:0 auto; }
    .cart-title { font-size:42px; line-height:1.08; font-weight:850; margin:0 0 24px; color:#071226; }
    .cart-panel { background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 12px 32px rgba(15,23,42,.06); overflow:hidden; }
    .cart-row { display:grid; grid-template-columns:110px minmax(0,1fr) 150px 180px 120px; gap:18px; align-items:center; padding:18px; border-bottom:1px solid #eef2f7; }
    .cart-row:last-child { border-bottom:0; }
    .cart-row img { width:110px; height:82px; object-fit:contain; background:#f8fafc; border-radius:8px; }
    .cart-name { color:#071226; font-size:17px; line-height:1.35; font-weight:800; text-decoration:none; }
    .cart-meta { color:#64748b; font-size:14px; margin:4px 0 0; }
    .cart-price, .cart-total { font-weight:850; color:#0a4588; }
    .cart-qty { display:flex; gap:8px; align-items:center; }
    .cart-qty input { width:72px; height:40px; border:1px solid #dbe3ea; border-radius:8px; padding:0 10px; }
    .cart-btn { min-height:40px; border:0; border-radius:8px; background:#1677ff; color:#fff; font-weight:800; padding:0 12px; }
    .cart-remove { border:0; background:transparent; color:#dc2626; font-weight:800; }
    .cart-summary { display:flex; justify-content:space-between; gap:16px; align-items:center; margin-top:18px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:22px; }
    .cart-summary strong { font-size:24px; color:#071226; }
    .cart-empty { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:34px; font-size:18px; color:#475569; }
    .cart-link { display:inline-flex; align-items:center; min-height:44px; border-radius:8px; background:#eef2f7; color:#0a4588; font-weight:800; padding:0 16px; text-decoration:none; }
    @media(max-width:850px){ .cart-row{grid-template-columns:84px 1fr;}.cart-price,.cart-qty,.cart-total,.cart-row form{grid-column:2}.cart-row img{width:84px;height:70px}.cart-summary{display:block}.cart-summary .cart-link{margin-top:14px} }
</style>

<section class="cart-page">
    <div class="cart-shell">
        <h1 class="cart-title">Cart</h1>

        @if($items->isEmpty())
            <div class="cart-empty">
                <p>Your cart is empty.</p>
                <a class="cart-link" href="{{ route('home') }}">Continue shopping</a>
            </div>
        @else
            <div class="cart-panel">
                @foreach($items as $item)
                    <article class="cart-row">
                        <img src="{{ $item->cart_image_url }}" alt="{{ $item->name }}">
                        <div>
                            <a class="cart-name" href="{{ route('products.show', ['product' => $item->slug]) }}">{{ $item->name }}</a>
                            <p class="cart-meta">{{ $item->category?->name ?? 'Product' }}</p>
                        </div>
                        <div class="cart-price">KSh {{ number_format((float) $item->price, 2) }}</div>
                        <form class="cart-qty" method="post" action="{{ route('cart.items.update', $item) }}">
                            @csrf
                            @method('patch')
                            <input type="number" name="quantity" value="{{ $item->cart_quantity }}" min="0" aria-label="Quantity for {{ $item->name }}">
                            <button class="cart-btn" type="submit">Update</button>
                        </form>
                        <div class="cart-total">KSh {{ number_format($item->line_total, 2) }}</div>
                        <form method="post" action="{{ route('cart.items.destroy', $item) }}">
                            @csrf
                            @method('delete')
                            <button class="cart-remove" type="submit">Remove</button>
                        </form>
                    </article>
                @endforeach
            </div>

            <div class="cart-summary">
                <strong>Total: KSh {{ number_format($total, 2) }}</strong>
                <a class="cart-link" href="{{ route('bookings.create') }}">Request checkout support</a>
            </div>
        @endif
    </div>
</section>
@endsection
