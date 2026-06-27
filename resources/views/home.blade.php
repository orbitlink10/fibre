@extends('layouts.app')
@section('title', 'MikroTik Kenya Marketplace')
@section('meta_description', 'Buy genuine MikroTik routers, WiFi systems, switches, LTE devices, antennas, and networking accessories in Kenya.')
@section('full_bleed', true)
@section('content')
@php
    $heroImage = $products->first()?->homepage_image_url ?: 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=1800&q=80';
    $visibleCategories = $categories->take(14);
    $fallbackProducts = collect([
        ['name' => 'Mikrotik Intercell 10 B38+B39', 'price' => 85000, 'image' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=700&q=80'],
        ['name' => 'Mikrotik mANT LTE 5o with R11e-LTE', 'price' => 25000, 'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=700&q=80'],
        ['name' => 'Mikrotik mANT LTE 5o', 'price' => 12000, 'image' => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?auto=format&fit=crop&w=700&q=80'],
        ['name' => 'Mikrotik R11e-LR8G', 'price' => 18000, 'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=700&q=80'],
    ]);
@endphp

<style>
html,body{margin:0!important;padding:0!important;overflow-x:hidden!important}
body{font-family:Arial,Helvetica,sans-serif!important;background:#f5f7fb!important;color:#031532!important}
.mk-store{min-height:100vh;background:#f5f7fb;color:#031532;font-family:Arial,Helvetica,sans-serif}
.mk-topbar{height:62px;display:flex;align-items:center;justify-content:center;background:linear-gradient(90deg,#164f92,#1e78ff);color:#fff;font-size:20px!important;font-weight:800;text-align:center}
.mk-header{background:#fff;border-bottom:1px solid #dde3eb}
.mk-header-inner{min-height:218px;max-width:1760px;margin:0 auto;padding:34px 66px;display:grid;grid-template-columns:360px minmax(360px,1fr) 270px;gap:24px;align-items:center}
.mk-logo{display:flex;align-items:center;gap:12px;text-decoration:none;color:#07265a}
.mk-logo-mark{width:166px;height:126px;display:grid;place-items:center;background:#fff}
.mk-logo-initials{font-size:68px;line-height:.85;font-weight:900;letter-spacing:-8px;color:#06295b}
.mk-logo-initials span{color:#1575ff}
.mk-logo-text{font-size:19px;font-weight:900;letter-spacing:.04em;text-transform:uppercase}
.mk-logo-text small{display:block;margin-top:3px;font-size:8px;letter-spacing:.12em;color:#1f4c80}
.mk-search{display:flex;gap:12px;align-items:center}
.mk-search input{width:100%;height:60px;border:1px solid #d8dee8;border-radius:10px;padding:0 18px;font-size:20px!important;font-weight:400;color:#1f2937;outline:none;background:#fff}
.mk-search input:focus{border-color:#1e78ff;box-shadow:0 0 0 4px rgba(30,120,255,.12)}
.mk-search button,.mk-card-action{border:0;border-radius:10px;background:#1d73f8;color:#fff;font-size:20px!important;font-weight:800;min-height:60px;padding:0 22px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}
.mk-account{display:flex;align-items:center;justify-content:flex-end;gap:15px;font-size:20px!important;white-space:nowrap}
.mk-account a{color:#020b1c;text-decoration:none}
.mk-account strong{font-weight:900}
.mk-main{max-width:1760px;margin:0 auto;padding:36px 66px 64px;display:grid;grid-template-columns:390px minmax(0,1fr);gap:24px}
.mk-sidebar{height:560px;overflow:auto;background:#fff;border:1px solid #dde3eb;border-radius:18px;padding:24px 22px}
.mk-sidebar h2{font-size:27px!important;line-height:1.1;margin:0 0 24px;font-weight:900;color:#031532}
.mk-category-list{display:grid;gap:3px}
.mk-category{display:block;padding:12px 15px;border-radius:10px;color:#00132f;text-decoration:none;font-size:23px!important;font-weight:400;line-height:1.36}
.mk-category:hover,.mk-category.is-active{background:#edf5ff;color:#0b519d}
.mk-content{min-width:0}
.mk-hero{min-height:255px;border-radius:20px;padding:68px 40px;background-image:linear-gradient(90deg,rgba(207,45,54,.92),rgba(242,119,62,.86)),url('{{ $heroImage }}');background-position:center;background-size:cover;color:#fff;display:flex;align-items:center;margin-bottom:24px;overflow:hidden}
.mk-hero-inner{max-width:1040px}
.mk-hero h1{font-size:38px!important;line-height:1.14;margin:0 0 14px;font-weight:900;color:#fff;letter-spacing:0}
.mk-hero p{font-size:23px!important;line-height:1.36;margin:0;color:#fff;max-width:1120px}
.mk-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px}
.mk-card{background:#fff;border:1px solid #dde3eb;border-radius:16px;overflow:hidden;min-width:0}
.mk-card-media{height:260px;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:18px}
.mk-card-media img{width:100%;height:100%;object-fit:contain;display:block}
.mk-card-body{padding:20px 18px 18px}
.mk-card h2{font-size:23px!important;line-height:1.24;margin:0 0 13px;color:#020b1c;font-weight:900}
.mk-store-name{font-size:18px!important;line-height:1.35;color:#667085;margin:0 0 13px}
.mk-price{font-size:23px!important;line-height:1.2;color:#06498e;font-weight:900;margin:0 0 15px}
.mk-card-action{min-height:54px;font-size:23px!important;padding:0 22px}
.mk-empty{background:#fff;border:1px solid #dde3eb;border-radius:16px;padding:32px;font-size:18px;color:#475467}
@media(max-width:1100px){
    .mk-header-inner{grid-template-columns:260px minmax(280px,1fr);padding:28px 26px;gap:24px}
    .mk-account{grid-column:1/-1;justify-content:flex-end}
    .mk-main{grid-template-columns:300px minmax(0,1fr);padding:28px 26px}
    .mk-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
}
@media(max-width:900px){
    .mk-topbar{height:auto;min-height:52px;padding:10px 18px;font-size:17px}
    .mk-header-inner{display:flex;flex-direction:column;align-items:stretch;min-height:0;padding:22px}
    .mk-logo{justify-content:center}
    .mk-search{flex-direction:column}
    .mk-search button{width:100%}
    .mk-account{justify-content:center;font-size:16px;flex-wrap:wrap}
    .mk-main{display:block;padding:22px}
    .mk-sidebar{height:auto;max-height:270px;margin-bottom:22px}
    .mk-category{font-size:16px}
    .mk-hero{min-height:220px;padding:36px 24px}
    .mk-hero h1{font-size:28px}
    .mk-hero p{font-size:17px}
    .mk-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:560px){
    .mk-logo-mark{width:138px;height:102px}
    .mk-logo-initials{font-size:56px}
    .mk-search input{height:54px;font-size:17px}
    .mk-grid{grid-template-columns:1fr}
    .mk-card-media{height:240px}
}
</style>

<div class="mk-store">
    <div class="mk-topbar">Laravel Multi-Vendor Marketplace (MySQL)</div>

    <header class="mk-header">
        <div class="mk-header-inner">
            <a class="mk-logo" href="{{ route('home') }}" aria-label="MikroTik Kenya home">
                <span class="mk-logo-mark">
                    <span>
                        <span class="mk-logo-initials">M<span>K</span></span>
                        <span class="mk-logo-text">MikroTik Kenya<small>Your No.1 shop for MikroTik</small></span>
                    </span>
                </span>
            </a>

            <form class="mk-search" action="{{ route('home') }}" method="get">
                <input type="search" name="search" value="{{ $search }}" placeholder="Search products, brands and categories" aria-label="Search products, brands and categories">
                <button type="submit">Search</button>
            </form>

            <nav class="mk-account" aria-label="Account links">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}"><strong>Register</strong></a>
                <a href="{{ route('home') }}"><strong>Cart (0)</strong></a>
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
                    @foreach(['Mikrotik', 'Mikrotik 60 GHz Products In Kenya', 'Mikrotik access points price in Kenya', 'Mikrotik accessories for Sale In Kenya', 'Mikrotik antennas Price In Kenya', 'Mikrotik Ethernet Routers'] as $category)
                        <a class="mk-category" href="{{ route('home', ['search' => $category]) }}">{{ $category }}</a>
                    @endforeach
                @endforelse
            </div>
        </aside>

        <section class="mk-content" aria-label="Products">
            <section class="mk-hero">
                <div class="mk-hero-inner">
                    <h1>MikroTik Kenya | Buy Genuine MikroTik Devices</h1>
                    <p>Find affordable MikroTik Kenya networking devices including routers, WiFi systems, and managed switches for reliable connectivity in East Africa.</p>
                </div>
            </section>

            @if($products->isNotEmpty())
                <div class="mk-grid">
                    @foreach($products as $product)
                        <article class="mk-card">
                            <div class="mk-card-media">
                                <img src="{{ $product->homepage_image_url }}" alt="{{ $product->name }}">
                            </div>
                            <div class="mk-card-body">
                                <h2>{{ $product->name }}</h2>
                                <p class="mk-store-name">{{ $product->category?->name ?? 'Almar Market Official Store' }}</p>
                                <p class="mk-price">KSh {{ number_format((float) $product->price, 2) }}</p>
                                <a class="mk-card-action" href="{{ route('home', ['search' => $product->name]) }}">View</a>
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
                                <h2>{{ $product['name'] }}</h2>
                                <p class="mk-store-name">Almar Market Official Store</p>
                                <p class="mk-price">KSh {{ number_format($product['price'], 2) }}</p>
                                <a class="mk-card-action" href="{{ route('home', ['search' => $product['name']]) }}">View</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </main>
</div>
@endsection
