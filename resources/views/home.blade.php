@extends('layouts.app')
@section('title', 'Fiber Optics Kenya')
@section('meta_description', 'Shop fiber optic networking products, installation accessories, routers, switches, and connectivity equipment in Kenya.')
@section('full_bleed', true)
@section('content')
@php
    $sourceBase = 'https://mikrotikkenya.co.ke';
    $heroImage = $content['home_hero_image_url'] ?? $sourceBase.'/uploads/homepage-content/20260512190532-ffuavwjom9.webp';
    $brandText = $content['home_site_brand'] ?? 'Fiber Optics Kenya';
    $logoImage = trim((string) ($content['home_logo_image_url'] ?? ''));
    $heroTitle = $content['home_hero_title'] ?? 'Fiber Optics Kenya | Buy Genuine Networking Devices';
    $heroDescription = $content['home_hero_description'] ?? 'Find affordable fiber optic cables, routers, switches, SFP modules, and networking accessories for reliable connectivity in East Africa.';
    $servicesTitle = $content['home_services_title'] ?? 'Products';
    $servicesSubtitle = $content['home_services_subtitle'] ?? 'Fiber optic products, networking equipment, and installation support across Kenya.';
    $cartCount = collect(session('cart', []))->sum();
    $visibleCategories = $categories->take(24);
    $fallbackProducts = collect([
        ['name' => 'Mikrotik Intercell 10 B38+B39', 'price' => 85000, 'image' => 'https://mikrotikkenya.co.ke/uploads/products/20260623092341-vsplsq9n5r.jpg'],
        ['name' => 'Mikrotik mANT LTE 5o with R11e-LTE', 'price' => 25000, 'image' => 'https://mikrotikkenya.co.ke/uploads/products/20260623092452-wkv4mqomfy.jpg'],
        ['name' => 'Mikrotik mANT LTE 5o', 'price' => 12000, 'image' => 'https://mikrotikkenya.co.ke/uploads/products/20260623092601-qb2l4tmxyt.jpg'],
        ['name' => 'Mikrotik R11e-LR8G', 'price' => 18000, 'image' => 'https://mikrotikkenya.co.ke/uploads/products/20260623092724-itavqqqkfj.jpg'],
    ]);
@endphp

<style>
html,body{margin:0!important;padding:0!important;overflow-x:hidden!important}
*,*::before,*::after{box-sizing:border-box}
body{font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif!important;background:#f6f7fb!important;color:#111827!important}
.mk-store{min-height:100vh;background:#f6f7fb;color:#111827;font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif}
.mk-topbar{height:auto;min-height:42px;padding:10px;display:flex;align-items:center;justify-content:center;background:linear-gradient(90deg,#0a4588,#1677ff);color:#fff;font-size:16px!important;line-height:1.35;font-weight:600;text-align:center}
.mk-header{background:#fff;border-bottom:1px solid #e5e7eb}
.mk-header-inner{max-width:1760px;margin:0 auto;padding:12px 66px;display:grid;grid-template-columns:420px minmax(360px,1fr) 270px;gap:16px;align-items:center}
.mk-logo{display:inline-flex;flex-direction:column;align-items:flex-start;justify-content:center;width:400px;height:150px;min-width:0;overflow:hidden;text-decoration:none;color:#0a4588}
.mk-logo-img{display:block;max-width:380px;max-height:128px;width:auto;height:auto;object-fit:contain}
.mk-logo-main{display:block;font-size:31px!important;line-height:1.05;font-weight:900;letter-spacing:0;color:#0a4588}
.mk-logo-sub{display:block;margin-top:7px;font-size:12px!important;line-height:1.2;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#1677ff}
.mk-search{display:flex;gap:12px;align-items:center}
.mk-search input{width:100%;height:56px;border:1px solid #e5e7eb;border-radius:10px;padding:0 18px;font-size:18px!important;font-weight:400;color:#111827;outline:none;background:#fff}
.mk-search input::placeholder{color:#6b7280;opacity:1}
.mk-search input:focus{border-color:#1677ff;box-shadow:0 0 0 4px rgba(22,119,255,.12)}
.mk-search button,.mk-card-action{border:0;border-radius:10px;background:#1677ff;color:#fff;font-size:16px!important;font-weight:700;min-height:56px;padding:0 22px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer}
.mk-account{display:flex;align-items:center;justify-content:flex-end;gap:15px;font-size:16px!important;white-space:nowrap}
.mk-account a{color:#020b1c;text-decoration:none}
.mk-account strong{font-weight:700}
.mk-main{max-width:1760px;margin:0 auto;padding:18px 66px 56px;display:grid;grid-template-columns:390px minmax(0,1fr);gap:18px}
.mk-sidebar{height:500px;overflow:auto;background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px 16px}
.mk-sidebar h2{font-size:21px!important;line-height:1.12;margin:0 0 14px;font-weight:800;color:#031532}
.mk-category-list{display:grid;gap:1px}
.mk-category{display:block;padding:9px 12px;border-radius:8px;color:#00132f;text-decoration:none;font-size:16px!important;font-weight:400;line-height:1.32}
.mk-category:hover,.mk-category.is-active{background:#eff6ff;color:#0a4588}
.mk-content{min-width:0}
.mk-hero{min-height:210px;border-radius:14px;padding:34px 40px;background-image:linear-gradient(120deg,rgba(198,31,31,.82),rgba(234,88,12,.72)),url('{{ $heroImage }}');background-position:center;background-size:cover;color:#fff;display:flex;align-items:center;margin-bottom:18px;overflow:hidden}
.mk-hero-inner{max-width:920px}
.mk-hero h1{font-size:30px!important;line-height:1.14;margin:0 0 10px;font-weight:800;color:#fff;letter-spacing:0}
.mk-hero p{font-size:18px!important;line-height:1.42;margin:0;color:#fff;max-width:900px}
.mk-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.mk-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;min-width:0}
.mk-card-media{height:190px;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:12px}
.mk-card-media img{width:100%;height:100%;object-fit:contain;display:block}
.mk-card-body{padding:12px}
.mk-card h2{font-size:15px!important;line-height:1.3;margin:0 0 8px;color:#020b1c;font-weight:700}
.mk-card h2 a{color:inherit;text-decoration:none}
.mk-store-name{font-size:13px!important;line-height:1.35;color:#6b7280;margin:0 0 8px}
.mk-price{font-size:16px!important;line-height:1.2;color:#0a4588;font-weight:700;margin:0 0 10px}
.mk-card-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.mk-card-action{min-height:40px;font-size:14px!important;padding:0 14px}
.mk-card-action.secondary{background:#eef2f7;color:#0a4588}
.mk-empty{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:32px;font-size:18px;color:#475467}
.mk-section-head{display:flex;align-items:end;justify-content:space-between;gap:18px;margin:0 0 16px}
.mk-section-head h2{font-size:28px!important;line-height:1.15;margin:0;color:#031532;font-weight:850;white-space:pre-line}
.mk-section-head p{max-width:760px;margin:0;color:#526174;font-size:16px!important;line-height:1.45}
.mk-info{grid-column:1/-1;margin-top:18px;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.mk-info-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px;min-width:0}
.mk-info-card h2{font-size:18px!important;line-height:1.24;margin:0 0 10px;color:#031532;font-weight:800}
.mk-info-card p{font-size:15px!important;line-height:1.55;margin:0 0 12px;color:#475467}
.mk-info-card strong{display:block;color:#0a4588;font-size:17px!important}
.mk-long-content{grid-column:1/-1;margin-top:18px;background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:28px;color:#1f2937;font-size:18px!important;line-height:1.65}
.mk-long-content h1,.mk-long-content h2,.mk-long-content h3{color:#031532;font-weight:850;line-height:1.2;margin:0 0 14px}
.mk-long-content h1{font-size:32px!important}
.mk-long-content h2{font-size:26px!important;margin-top:24px}
.mk-long-content h3{font-size:22px!important;margin-top:20px}
.mk-long-content p{margin:0 0 16px}
.mk-long-content a{color:#0a4588;font-weight:700}
@media(max-width:1100px){
    .mk-header-inner{grid-template-columns:320px minmax(280px,1fr);padding:12px 26px;gap:16px}
    .mk-logo{width:310px;height:120px}
    .mk-logo-img{max-width:300px;max-height:102px}
    .mk-account{grid-column:1/-1;justify-content:flex-end}
    .mk-main{grid-template-columns:280px minmax(0,1fr);padding:18px 26px}
    .mk-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
    .mk-info{grid-template-columns:1fr}
}
@media(max-width:900px){
    .mk-topbar{height:auto;min-height:52px;padding:10px 18px;font-size:17px}
    .mk-header-inner{display:flex;flex-direction:column;align-items:stretch;min-height:0;padding:14px 22px}
    .mk-logo{justify-content:center;align-items:center;width:min(380px,100%);height:128px;margin:0 auto}
    .mk-logo-img{max-width:min(360px,100%);max-height:112px}
    .mk-search{flex-direction:column}
    .mk-search button{width:100%}
    .mk-account{justify-content:center;font-size:16px;flex-wrap:wrap}
    .mk-main{display:block;padding:22px}
    .mk-sidebar{height:auto;max-height:270px;margin-bottom:22px}
    .mk-category{font-size:16px!important}
    .mk-hero{min-height:180px;padding:28px 24px}
    .mk-hero h1{font-size:26px!important}
    .mk-hero p{font-size:16px!important}
    .mk-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
    .mk-section-head{display:block}
    .mk-section-head p{margin-top:8px}
}
@media(max-width:560px){
    .mk-logo{width:min(320px,100%);height:112px}
    .mk-logo-img{max-width:min(300px,100%);max-height:96px}
    .mk-search input{height:54px;font-size:16px!important}
    .mk-grid{grid-template-columns:1fr}
    .mk-card-media{height:180px}
}
</style>

<div class="mk-store">
    <div class="mk-topbar">{{ $brandText }}</div>

    <header class="mk-header">
        <div class="mk-header-inner">
            <a class="mk-logo" href="{{ route('home') }}" aria-label="{{ $brandText }} home">
                @if($logoImage !== '')
                    <img class="mk-logo-img" src="{{ $logoImage }}" alt="{{ $brandText }}">
                @else
                    <span class="mk-logo-main">{{ $brandText }}</span>
                    <span class="mk-logo-sub">Networking & Connectivity</span>
                @endif
            </a>

            <form class="mk-search" action="{{ route('home') }}" method="get">
                <input type="search" name="search" value="{{ $search }}" placeholder="Search products, brands and categories" aria-label="Search products, brands and categories">
                <button type="submit">Search</button>
            </form>

            <nav class="mk-account" aria-label="Account links">
                <a href="{{ $content['home_phone_href'] ?? '#' }}"><strong>{{ $content['home_phone'] ?? '' }}</strong></a>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}"><strong>Register</strong></a>
                <a href="{{ route('cart.show') }}"><strong>Cart ({{ $cartCount }})</strong></a>
            </nav>
        </div>
    </header>

    <main class="mk-main">
        <aside class="mk-sidebar" aria-label="Product categories">
            <h2>Categories</h2>
            <div class="mk-category-list">
                <a class="mk-category is-active" href="{{ route('home') }}">All Products</a>
                @forelse($visibleCategories as $category)
                    <a class="mk-category" href="{{ route('home', ['search' => $category->name]) }}">{{ $category->name }}</a>
                @empty
                    @foreach(['Fiber Optic Cables', 'Fiber Routers', 'Fiber Switches', 'SFP Modules', 'Patch Panels', 'Networking Accessories'] as $category)
                        <a class="mk-category" href="{{ route('home', ['search' => $category]) }}">{{ $category }}</a>
                    @endforeach
                @endforelse
            </div>
        </aside>

        <section class="mk-content" aria-label="Products">
            <section class="mk-hero">
                <div class="mk-hero-inner">
                    <h1>{!! nl2br(e($heroTitle)) !!}</h1>
                    <p>{{ $heroDescription }}</p>
                </div>
            </section>

            <div class="mk-section-head">
                <div>
                    <h2>{!! nl2br(e($servicesTitle)) !!}</h2>
                    <p>{{ $servicesSubtitle }}</p>
                </div>
                <a class="mk-card-action" href="{{ route('bookings.create') }}">{{ $content['home_primary_cta'] ?? 'Request For Installation' }}</a>
            </div>

            @if($products->isNotEmpty())
                <div class="mk-grid">
                    @foreach($products as $product)
                        <article class="mk-card">
                            <div class="mk-card-media">
                                <img src="{{ $product->homepage_image_url }}" alt="{{ $product->name }}">
                            </div>
                            <div class="mk-card-body">
                                <h2><a href="{{ route('products.show', ['product' => $product->slug]) }}">{{ $product->name }}</a></h2>
                                <p class="mk-store-name">{{ $product->category?->name ?? 'Almar Market Official Store' }}</p>
                                <p class="mk-price">KSh {{ number_format((float) $product->price, 2) }}</p>
                                <div class="mk-card-actions">
                                    <a class="mk-card-action secondary" href="{{ route('products.show', ['product' => $product->slug]) }}">View</a>
                                    <form method="post" action="{{ route('cart.items.store', $product) }}">
                                        @csrf
                                        <button class="mk-card-action" type="submit">Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @elseif($search !== '')
                <div class="mk-empty">No products matched "{{ $search }}". Try another product, brand, or category.</div>
            @else
                <div class="mk-grid">
                    @foreach($fallbackProducts as $product)
                        <article class="mk-card">
                            <div class="mk-card-media">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                            </div>
                            <div class="mk-card-body">
                                <h2><a href="{{ route('home', ['search' => $product['name']]) }}">{{ $product['name'] }}</a></h2>
                                <p class="mk-store-name">Almar Market Official Store</p>
                                <p class="mk-price">KSh {{ number_format($product['price'], 2) }}</p>
                                <a class="mk-card-action" href="{{ route('home', ['search' => $product['name']]) }}">View</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            <section class="mk-info" aria-label="Homepage highlights">
                @for($i = 1; $i <= 3; $i++)
                    <article class="mk-info-card">
                        <h2>{{ $content['home_kit_title_'.$i] ?? '' }}</h2>
                        <p>{{ $content['home_kit_text_'.$i] ?? '' }}</p>
                        <strong>{{ $content['home_kit_price_'.$i] ?? '' }}</strong>
                    </article>
                @endfor
            </section>

            <section class="mk-long-content" aria-label="{{ $content['home_testimonials_title'] ?? 'Homepage content' }}">
                <h2>{{ $content['home_testimonials_title'] ?? 'Homepage content' }}</h2>
                {!! $content['home_empty_testimonials'] ?? '' !!}
            </section>
        </section>
    </main>
</div>
@endsection
