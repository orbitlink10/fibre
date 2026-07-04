@extends('layouts.app')

@section('title', $category->name.' | Fiber Optics Kenya')
@section('meta_description', $category->meta_description ?: $category->name.' products from Fiber Optics Kenya.')

@section('content')
<style>
    .category-page { background:#f6f7fb; color:#0f172a; padding:42px 20px 72px; }
    .category-shell { max-width:1180px; margin:0 auto; }
    .category-hero { display:grid; grid-template-columns:minmax(0, 1.1fr) minmax(280px, .75fr); gap:28px; align-items:center; margin-bottom:32px; }
    .category-copy { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:30px; box-shadow:0 12px 32px rgba(15,23,42,.06); }
    .category-label { margin:0 0 10px; color:#0a4588; font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
    .category-title { margin:0 0 16px; color:#071226; font-size:clamp(34px, 5vw, 58px); line-height:1.02; font-weight:850; }
    .category-description { color:#475569; font-size:18px; line-height:1.65; }
    .category-description p:last-child { margin-bottom:0; }
    .category-media { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:18px; box-shadow:0 12px 32px rgba(15,23,42,.06); }
    .category-media img { display:block; width:100%; aspect-ratio:1.45 / 1; object-fit:cover; border-radius:8px; }
    .category-toolbar { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:18px; }
    .category-toolbar h2 { margin:0; color:#071226; font-size:26px; font-weight:850; }
    .category-count { margin:0; color:#64748b; font-size:15px; }
    .category-grid { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:18px; }
    .category-product { background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; text-decoration:none; color:#071226; box-shadow:0 10px 24px rgba(15,23,42,.05); }
    .category-product img { display:block; width:100%; aspect-ratio:1 / .82; object-fit:contain; padding:16px; background:#fff; }
    .category-product-body { padding:0 16px 18px; }
    .category-product h3 { margin:0 0 8px; font-size:17px; line-height:1.3; font-weight:850; }
    .category-price { margin:0; color:#0a4588; font-size:16px; font-weight:850; }
    .category-empty { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:30px; color:#64748b; }
    @media(max-width:1000px){ .category-grid{grid-template-columns:repeat(3, minmax(0, 1fr));} }
    @media(max-width:820px){ .category-hero{grid-template-columns:1fr;} .category-grid{grid-template-columns:repeat(2, minmax(0, 1fr));} }
    @media(max-width:520px){ .category-page{padding:28px 14px 54px;} .category-copy{padding:22px;} .category-grid{grid-template-columns:1fr;} .category-toolbar{align-items:flex-start; flex-direction:column;} }
</style>

<section class="category-page">
    <div class="category-shell">
        <div class="category-hero">
            <section class="category-copy">
                <p class="category-label">Category</p>
                <h1 class="category-title">{{ $category->name }}</h1>
                @if(trim((string) $category->description) !== '')
                    <div class="category-description">{!! $category->description !!}</div>
                @else
                    <p class="category-description">Browse {{ $category->name }} products available from Fiber Optics Kenya.</p>
                @endif
            </section>
            <figure class="category-media">
                <img src="{{ $category->public_image_url }}" alt="{{ $category->name }}">
            </figure>
        </div>

        <div class="category-toolbar">
            <h2>Products</h2>
            <p class="category-count">{{ $products->total() }} {{ Str::plural('item', $products->total()) }}</p>
        </div>

        @if($products->count())
            <div class="category-grid">
                @foreach($products as $product)
                    <a class="category-product" href="{{ route('products.show', ['product' => $product->slug]) }}">
                        <img src="{{ $product->public_image_url }}" alt="{{ $product->name }}">
                        <div class="category-product-body">
                            <h3>{{ $product->name }}</h3>
                            <p class="category-price">KSh {{ number_format((float) $product->price, 2) }}</p>
                        </div>
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
