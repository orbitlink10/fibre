@extends('layouts.app')

@section('title', 'Fiber Technicians in Kenya')
@section('meta_description', 'Find approved fiber optic technicians in Kenya for splicing, FTTH installation, network testing, cabling, Wi-Fi, and connectivity support.')
@section('canonical', route('technicians.public'))

@php
    $listingUrl = auth()->check() ? route('technicians.index') : route('register');
    $technicianSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Fiber Technicians in Kenya',
        'url' => route('technicians.public'),
        'description' => 'Approved fiber optic technician directory for installation and connectivity support in Kenya.',
    ];
@endphp

@section('schema')
{!! json_encode($technicianSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
@endsection

@section('content')
<style>
    .technician-page {
        background: #f6f9fb;
    }

    .technician-hero {
        background: linear-gradient(120deg, rgba(6, 63, 112, .92), rgba(13, 127, 92, .88)), url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1800&q=80') center/cover;
        color: #fff;
        padding: 72px 0 56px;
    }

    .technician-hero h1 {
        max-width: 760px;
        margin: 0 0 14px;
        font-size: clamp(2rem, 4vw, 3.25rem);
        line-height: 1.08;
        font-weight: 800;
    }

    .technician-hero p {
        max-width: 760px;
        margin: 0;
        color: rgba(255, 255, 255, .9);
        font-size: 1.1rem;
        line-height: 1.55;
    }

    .technician-directory {
        padding: 42px 0 64px;
    }

    .technician-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .technician-toolbar h2 {
        margin: 0;
        color: #0f172a;
        font-weight: 800;
    }

    .technician-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .technician-card {
        min-height: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 22px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, .07);
    }

    .technician-card h3 {
        margin: 0 0 6px;
        color: #0f172a;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .technician-meta {
        color: #64748b;
        font-size: .95rem;
        line-height: 1.5;
    }

    .technician-specialties {
        margin: 16px 0;
        color: #1f2937;
        line-height: 1.55;
    }

    .technician-services {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: 16px 0;
    }

    .technician-service {
        border: 1px solid #dbeafe;
        border-radius: 999px;
        background: #eff6ff;
        color: #075985;
        padding: 5px 10px;
        font-size: .85rem;
        font-weight: 700;
    }

    .technician-contact {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 18px;
    }

    .technician-empty {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 28px;
        color: #475569;
    }

    @media (max-width: 991.98px) {
        .technician-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .technician-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 575.98px) {
        .technician-hero {
            padding: 54px 0 42px;
        }

        .technician-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="technician-page">
    <section class="technician-hero">
        <div class="container">
            <h1>Fiber Technicians in Kenya</h1>
            <p>Find approved fiber optic technicians for splicing, FTTH installation, cable testing, structured cabling, Wi-Fi setup, and network troubleshooting.</p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a class="btn btn-brand btn-lg" href="{{ $listingUrl }}">List Yourself as a Technician</a>
                <a class="btn btn-light btn-lg" href="{{ route('bookings.create') }}">Request Installation Help</a>
            </div>
        </div>
    </section>

    <section class="technician-directory">
        <div class="container">
            <div class="technician-toolbar">
                <div>
                    <h2>Approved Technicians</h2>
                    <p class="text-muted mb-0">{{ $technicians->total() }} technician{{ $technicians->total() === 1 ? '' : 's' }} available</p>
                </div>
                <a class="btn btn-outline-primary rounded-pill" href="{{ $listingUrl }}">Create Technician Listing</a>
            </div>

            @if($technicians->isNotEmpty())
                <div class="technician-grid">
                    @foreach($technicians as $technician)
                        <article class="technician-card">
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <h3>{{ $technician->name }}</h3>
                                    <div class="technician-meta">{{ $technician->category->name ?? 'Fiber technician' }}</div>
                                </div>
                                <span class="badge text-bg-success align-self-start">Approved</span>
                            </div>

                            <div class="technician-meta mt-2">
                                <i class="bi bi-geo-alt"></i>
                                {{ collect([$technician->location, $technician->county])->filter()->join(', ') ?: 'Kenya' }}
                            </div>

                            @if($technician->experience_years !== null)
                                <div class="technician-meta mt-1">{{ $technician->experience_years }} year{{ $technician->experience_years === 1 ? '' : 's' }} experience</div>
                            @endif

                            <p class="technician-specialties">{{ \Illuminate\Support\Str::limit($technician->specialties ?: $technician->qualification_summary, 180) }}</p>

                            @if($technician->services->isNotEmpty())
                                <div class="technician-services">
                                    @foreach($technician->services->take(4) as $service)
                                        <span class="technician-service">{{ $service->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="technician-contact">
                                <a class="btn btn-sm btn-brand rounded-pill" href="tel:{{ preg_replace('/\D+/', '', $technician->phone) }}">Call {{ $technician->phone }}</a>
                                @if($technician->email)
                                    <a class="btn btn-sm btn-outline-primary rounded-pill" href="mailto:{{ $technician->email }}">Email</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-4">{{ $technicians->links() }}</div>
            @else
                <div class="technician-empty">
                    <h2 class="h5">No approved technicians are listed yet.</h2>
                    <p class="mb-3">Technicians can sign up, upload qualifications, and submit their listing for approval.</p>
                    <a class="btn btn-brand rounded-pill" href="{{ $listingUrl }}">Submit Technician Listing</a>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
