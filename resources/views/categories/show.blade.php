@extends('layouts.app')

@section('title', $category->name.' | Fiber Optics Kenya')
@section('meta_description', $category->meta_description ?: $category->name.' products from Fiber Optics Kenya.')

@section('content')
@php
    $categoryIntro = trim((string) ($category->meta_description ?: Str::limit(strip_tags((string) $category->description), 280)));
    $categoryIntro = $categoryIntro !== ''
        ? $categoryIntro
        : 'Shop curated '.$category->name.' products with trusted options for Kenyan homes and businesses.';
@endphp
<style>
    .category-page { background:linear-gradient(135deg,#f8fbff 0%,#eef5ff 42%,#eefcff 100%); color:#081225; padding:64px 20px 84px; }
    .category-shell { max-width:1420px; margin:0 auto; }
    .category-hero { display:grid; grid-template-columns:minmax(0, 1fr) 500px; gap:48px; align-items:center; min-height:440px; }
    .category-breadcrumb { display:flex; flex-wrap:wrap; align-items:center; gap:12px; margin:0 0 26px; color:#334b6b; font-size:18px; font-weight:650; }
    .category-breadcrumb a { color:#1c3558; text-decoration:none; }
    .category-breadcrumb span { color:#71819a; }
    .category-pill { display:inline-flex; align-items:center; min-height:38px; padding:0 18px; border:1px solid #d3dae6; border-radius:999px; background:#e8edf5; color:#071226; font-size:14px; font-weight:850; letter-spacing:.16em; text-transform:uppercase; }
    .category-title { max-width:880px; margin:24px 0 18px; color:#071226; font-size:clamp(42px, 5vw, 72px); line-height:1.04; font-weight:850; }
    .category-description { max-width:760px; color:#243d62; font-size:22px; line-height:1.45; }
    .category-description p { margin:0 0 14px; }
    .category-actions { display:flex; flex-wrap:wrap; gap:14px; margin-top:30px; }
    .category-button { display:inline-flex; align-items:center; justify-content:center; min-height:52px; padding:0 22px; border-radius:999px; border:1px solid #d5dce7; background:#fff; color:#071226; text-decoration:none; font-size:20px; font-weight:800; box-shadow:0 12px 28px rgba(15,23,42,.08); }
    .category-button.primary { border-color:#ff901f; background:#ff901f; color:#fff; }
    .category-benefits { display:flex; flex-wrap:wrap; gap:12px; margin-top:30px; }
    .category-benefit { display:inline-flex; align-items:center; gap:10px; min-height:44px; padding:0 18px; border:1px solid #d9e0ea; border-radius:999px; background:rgba(255,255,255,.86); color:#071226; font-size:17px; font-weight:750; box-shadow:0 12px 26px rgba(15,23,42,.07); }
    .category-benefit i { color:#ff8310; font-size:18px; }
    .category-summary { border-radius:28px; background:rgba(255,255,255,.92); padding:28px; box-shadow:0 24px 70px rgba(15,23,42,.14); }
    .category-count-card { display:flex; align-items:center; justify-content:space-between; min-height:74px; margin-bottom:24px; padding:0 22px; border:1px solid #dbe3ee; border-radius:16px; background:#f2f6fc; }
    .category-count-card span { color:#607493; font-size:16px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; }
    .category-count-card strong { color:#071226; font-size:34px; line-height:1; font-weight:900; }
    .category-summary img { display:block; width:190px; max-width:100%; aspect-ratio:1 / 1; object-fit:cover; border-radius:26px; margin-bottom:26px; }
    .category-summary p { margin:0; color:#344967; font-size:20px; line-height:1.45; }
    .category-products { background:#f8fafc; padding:72px 20px 86px; }
    .category-products-shell { max-width:1420px; margin:0 auto; }
    .category-products-head { display:grid; grid-template-columns:minmax(0, 1fr) auto; gap:24px; align-items:end; margin-bottom:30px; }
    .category-products-head .category-pill { margin-bottom:20px; }
    .category-products h2 { margin:0 0 12px; color:#071226; font-size:clamp(34px, 4vw, 54px); line-height:1.08; font-weight:850; }
    .category-products-subtitle { max-width:720px; margin:0; color:#405473; font-size:21px; line-height:1.45; }
    .category-showing { display:flex; align-items:center; gap:22px; color:#607493; font-size:18px; white-space:nowrap; }
    .category-showing a { display:inline-flex; align-items:center; min-height:46px; padding:0 18px; border:1px solid #d7dee9; border-radius:999px; background:#fff; color:#071226; text-decoration:none; font-weight:800; }
    .category-grid { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:28px; }
    .category-product { display:flex; flex-direction:column; min-width:0; min-height:100%; padding:16px 16px 18px; border:1px solid #e0e6ee; border-radius:18px; background:#fff; color:#071226; text-decoration:none; box-shadow:0 16px 34px rgba(15,23,42,.06); }
    .category-product img { display:block; width:100%; aspect-ratio:1 / .94; object-fit:contain; border:1px solid #dbe3ee; border-radius:14px; background:#fff; }
    .category-product-meta { margin:12px 0 10px; color:#6a7b95; font-size:16px; line-height:1.25; font-weight:750; }
    .category-product h3 { margin:0 0 14px; color:#071226; font-size:20px; line-height:1.28; font-weight:850; }
    .category-price { margin:auto 0 0; color:#0a4588; font-size:18px; font-weight:850; }
    .category-empty { border:1px solid #e0e6ee; border-radius:18px; background:#fff; padding:34px; color:#607493; font-size:18px; }
    @media(max-width:1180px){ .category-hero{grid-template-columns:1fr;} .category-summary{max-width:620px;} .category-grid{grid-template-columns:repeat(3, minmax(0, 1fr));} }
    @media(max-width:860px){ .category-page{padding:40px 16px 56px;} .category-products{padding:52px 16px 66px;} .category-products-head{grid-template-columns:1fr;} .category-showing{flex-wrap:wrap; white-space:normal;} .category-grid{grid-template-columns:repeat(2, minmax(0, 1fr)); gap:18px;} }
    @media(max-width:560px){ .category-title{font-size:38px;} .category-description{font-size:18px;} .category-summary{padding:20px; border-radius:20px;} .category-grid{grid-template-columns:1fr;} .category-product h3{font-size:18px;} }
</style>

<section class="category-page">
    <div class="category-shell">
        <div class="category-hero">
            <section>
                <nav class="category-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('home') }}#kits">Shop</a>
                    <span>/</span>
                    <strong>{{ $category->name }}</strong>
                </nav>

                <span class="category-pill">Category</span>
                <h1 class="category-title">{{ $category->name }}</h1>

                <p class="category-description">{{ $categoryIntro }}</p>

                <div class="category-actions">
                    <a class="category-button primary" href="#category-products">Shop products</a>
                    <a class="category-button" href="{{ route('home') }}">All categories</a>
                </div>

                <div class="category-benefits" aria-label="Shopping benefits">
                    <span class="category-benefit"><i class="bi bi-shield-check"></i> Genuine warranty</span>
                    <span class="category-benefit"><i class="bi bi-truck"></i> Fast delivery</span>
                    <span class="category-benefit"><i class="bi bi-headset"></i> Expert support</span>
                </div>
            </section>

            <aside class="category-summary" aria-label="Category summary">
                <div class="category-count-card">
                    <span>Products</span>
                    <strong>{{ $products->total() }}</strong>
                </div>
                <img src="{{ $category->public_image_url }}" alt="{{ $category->name }}">
                <p>{{ $categoryIntro }}</p>
            </aside>
        </div>
    </div>
</section>

<section id="category-products" class="category-products">
    <div class="category-products-shell">
        <div class="category-products-head">
            <div>
                <span class="category-pill">Featured</span>
                <h2>Top picks in {{ $category->name }}</h2>
                <p class="category-products-subtitle">Compare price points, specs, and bundles tailored to Kenyan homes and businesses.</p>
            </div>
            <div class="category-showing">
                @if($products->total() > 0)
                    <span>Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} items</span>
                @else
                    <span>No items yet</span>
                @endif
                <a href="{{ route('home') }}">All categories</a>
            </div>
        </div>

        @if($products->count())
            <div class="category-grid">
                @foreach($products as $product)
                    <a class="category-product" href="{{ route('products.show', ['product' => $product->slug]) }}">
                        <img src="{{ $product->public_image_url }}" alt="{{ $product->name }}">
                        <p class="category-product-meta">{{ $category->name }}</p>
                        <h3>{{ $product->name }}</h3>
                        <p class="category-price">KSh {{ number_format((float) $product->price, 2) }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        @else
            <div class="category-empty">No products have been added to this category yet.</div>
        @endif
    </div>
</section>
@endsection
