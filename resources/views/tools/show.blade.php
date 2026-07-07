@extends('layouts.app')

@section('title', $tool['title'].' | Fiber Optics Kenya')
@section('meta_description', $tool['meta'])
@section('canonical', url($slug))
@php
    $toolSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebApplication',
                'name' => $tool['title'],
                'description' => $tool['summary'],
                'url' => url($slug),
                'applicationCategory' => 'UtilityApplication',
                'operatingSystem' => 'Web',
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => collect($tool['faqs'])->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['a'],
                    ],
                ])->values()->all(),
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $tool['title'], 'item' => url($slug)],
                ],
            ],
        ],
    ];
@endphp
@section('schema')
{!! json_encode($toolSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
@endsection

@section('content')
<style>
    .tool-page { background:#f5f8fc; color:#101827; font-family:Inter, "Segoe UI", Arial, sans-serif; }
    .tool-hero { background:linear-gradient(135deg,#eef6ff,#f9fcff); border-bottom:1px solid #dce5f1; }
    .tool-shell { max-width:1180px; margin:0 auto; padding:42px 20px; }
    .tool-crumb { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px; color:#60728e; font-size:15px; }
    .tool-crumb a { color:#0a4588; text-decoration:none; font-weight:800; }
    .tool-hero-grid { display:grid; grid-template-columns:minmax(0,1fr) 360px; gap:34px; align-items:center; }
    .tool-kicker { display:inline-flex; padding:7px 13px; border:1px solid #cfd9e8; border-radius:999px; color:#0a4588; background:#fff; font-size:13px; font-weight:850; letter-spacing:.12em; text-transform:uppercase; }
    .tool-title { margin:18px 0 14px; font-size:clamp(40px,5vw,62px); line-height:1.02; font-weight:900; color:#071226; }
    .tool-summary { margin:0; max-width:760px; color:#304764; font-size:20px; line-height:1.55; }
    .tool-card { border:1px solid #dce5f1; border-radius:8px; background:#fff; padding:24px; box-shadow:0 18px 42px rgba(15,23,42,.08); }
    .tool-card h2 { margin:0 0 10px; font-size:24px; font-weight:900; color:#071226; }
    .tool-card p { margin:0 0 18px; color:#50627b; line-height:1.55; }
    .tool-button { display:inline-flex; align-items:center; justify-content:center; min-height:46px; padding:0 18px; border:0; border-radius:8px; background:#ff8a1f; color:#fff; text-decoration:none; font-weight:900; cursor:pointer; }
    .tool-main { max-width:1180px; margin:0 auto; padding:36px 20px 72px; display:grid; grid-template-columns:minmax(0,760px) 320px; gap:48px; align-items:start; }
    .tool-panel { border:1px solid #dce5f1; border-radius:8px; background:#fff; padding:24px; margin-bottom:28px; box-shadow:0 10px 28px rgba(15,23,42,.05); }
    .tool-panel h2 { margin:0 0 16px; color:#071226; font-size:28px; line-height:1.2; font-weight:900; }
    .tool-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
    .tool-field label { display:block; margin-bottom:6px; color:#24374f; font-size:14px; font-weight:800; }
    .tool-field input, .tool-field select, .tool-field textarea { width:100%; border:1px solid #cfd9e8; border-radius:8px; padding:11px 12px; color:#101827; background:#fff; font-size:15px; }
    .tool-field textarea { min-height:104px; resize:vertical; }
    .tool-result { margin-top:18px; border:1px solid #c9e3d5; border-radius:8px; padding:18px; background:#f1fbf5; color:#143622; font-weight:750; line-height:1.55; }
    .tool-result strong { display:block; color:#0a4588; font-size:24px; line-height:1.2; margin-bottom:6px; }
    .color-chip { display:inline-flex; align-items:center; gap:10px; margin-top:14px; padding:12px 14px; border:1px solid #d9e2ef; border-radius:8px; background:#fff; font-weight:850; }
    .color-dot { width:30px; height:30px; border-radius:50%; border:1px solid #b8c3d2; }
    .wizard-options { display:grid; gap:10px; }
    .wizard-options button { text-align:left; border:1px solid #d4deec; border-radius:8px; padding:12px 14px; background:#fff; font-weight:800; color:#17243a; }
    .article-content { color:#253246; font-size:18px; line-height:1.72; }
    .article-content h2 { margin:42px 0 14px; color:#071226; font-size:30px; line-height:1.2; font-weight:900; }
    .article-content p { margin:0 0 18px; }
    .article-content ul { margin:0 0 22px; padding-left:24px; }
    .faq-item { border-top:1px solid #e3e9f2; padding:18px 0; }
    .faq-item h3 { margin:0 0 8px; font-size:19px; font-weight:900; color:#071226; }
    .faq-item p { margin:0; color:#3d4f68; line-height:1.6; }
    .tool-aside { position:sticky; top:104px; }
    .aside-card { border:1px solid #dce5f1; border-radius:8px; background:#fff; padding:20px; margin-bottom:18px; }
    .aside-card h2 { margin:0 0 12px; color:#071226; font-size:20px; font-weight:900; }
    .related-list { display:grid; gap:12px; margin:0; padding:0; list-style:none; }
    .related-list a { color:#0a4588; text-decoration:none; font-weight:850; }
    .related-list p { margin:4px 0 0; color:#60728e; font-size:14px; line-height:1.35; }
    @media(max-width:980px){ .tool-hero-grid,.tool-main{grid-template-columns:1fr;} .tool-aside{position:static;} }
    @media(max-width:620px){ .tool-shell,.tool-main{padding-left:16px;padding-right:16px;} .tool-grid{grid-template-columns:1fr;} .tool-title{font-size:36px;} }
</style>

<article class="tool-page" data-tool="{{ $tool['type'] }}">
    <section class="tool-hero">
        <div class="tool-shell">
            <nav class="tool-crumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span>/</span><span>Free Fiber Tools</span><span>/</span><strong>{{ $tool['title'] }}</strong>
            </nav>
            <div class="tool-hero-grid">
                <div>
                    <span class="tool-kicker">Free Fiber Tool</span>
                    <h1 class="tool-title">{{ $tool['title'] }}</h1>
                    <p class="tool-summary">{{ $tool['summary'] }}</p>
                </div>
                <aside class="tool-card">
                    <h2>{{ $tool['cta']['title'] }}</h2>
                    <p>{{ $tool['cta']['text'] }}</p>
                    <a class="tool-button" href="{{ route('bookings.create') }}">{{ $tool['cta']['button'] }}</a>
                </aside>
            </div>
        </div>
    </section>

    <div class="tool-main">
        <main>
            <section class="tool-panel" aria-labelledby="tool-heading">
                <h2 id="tool-heading">Use the Tool</h2>
                @include('tools.partials.'.$tool['type'])
                <div class="tool-result" id="tool-result" aria-live="polite">
                    Enter your details to calculate a result.
                </div>
            </section>

            <section class="article-content">
                @foreach($tool['content'] as $section)
                    <h2>{{ $section['h2'] }}</h2>
                    @foreach($section['p'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                @endforeach

                <h2>Worked Examples</h2>
                <ul>
                    @foreach($tool['examples'] as $example)
                        <li>{{ $example }}</li>
                    @endforeach
                </ul>

                <h2>Frequently Asked Questions</h2>
                @foreach($tool['faqs'] as $faq)
                    <section class="faq-item">
                        <h3>{{ $faq['q'] }}</h3>
                        <p>{{ $faq['a'] }}</p>
                    </section>
                @endforeach
            </section>
        </main>

        <aside class="tool-aside">
            <section class="aside-card">
                <h2>Related Tools</h2>
                <ul class="related-list">
                    @foreach($related as $item)
                        <li>
                            <a href="{{ url($item['slug']) }}">{{ $item['title'] }}</a>
                            <p>{{ $item['summary'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
            <section class="aside-card">
                <h2>Commercial Support</h2>
                <p>Need a real quotation, route survey, splicing, testing, or troubleshooting visit?</p>
                <a class="tool-button" href="{{ route('bookings.create') }}">Request Fiber Technician</a>
            </section>
        </aside>
    </div>
</article>

@include('tools.scripts')
@endsection
