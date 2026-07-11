@extends('layouts.dashboard')

@section('title', 'Technicians')
@section('dashboard-title', 'Technicians')
@section('dashboard-subtitle', isset($technicians) ? 'Review fiber technician listings and qualification documents.' : 'List yourself as a fiber technician and upload your qualifications.')

@section('content')
<div class="px-3 pb-4">
    @isset($technicians)
        <div class="card dashboard-content-card overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Specialties</th>
                            <th>Qualification</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($technicians as $technician)
                            <tr>
                                <td>
                                    <strong>{{ $technician->name }}</strong>
                                    <div class="text-muted small">{{ $technician->category->name ?? 'No category selected' }}</div>
                                </td>
                                <td>
                                    <div>{{ $technician->phone }}</div>
                                    <div class="text-muted small">{{ $technician->email ?? $technician->user?->email }}</div>
                                </td>
                                <td>{{ collect([$technician->location, $technician->county])->filter()->join(', ') ?: '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($technician->specialties ?: '-', 90) }}</td>
                                <td>
                                    @if($technician->document_path)
                                        <a href="{{ route('media.image', ['path' => $technician->document_path]) }}" target="_blank" rel="noopener">View document</a>
                                    @else
                                        <span class="text-muted">Missing</span>
                                    @endif
                                </td>
                                <td><span class="badge text-bg-light status">{{ $technician->verification_status }}</span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary rounded-pill" href="{{ route('admin.providers.edit', $technician) }}">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No technician listings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $technicians->links() }}</div>
    @else
        @php
            $selectedServices = collect(old('service_ids', $provider?->services->pluck('id')->all() ?? []))
                ->map(fn ($id) => (int) $id)
                ->all();
        @endphp

        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card dashboard-content-card">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                            <div>
                                <h2 class="h5 mb-1">{{ $provider ? 'Update Technician Listing' : 'Create Technician Listing' }}</h2>
                                <p class="text-muted mb-0">Your listing is reviewed before it is approved for technician work.</p>
                            </div>
                            @if($provider)
                                <span class="badge text-bg-light status align-self-start">{{ $provider->verification_status }}</span>
                            @endif
                        </div>

                        <form method="post" action="{{ $provider ? route('technicians.update') : route('technicians.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if($provider)
                                @method('put')
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full name or company name</label>
                                    <input class="form-control" name="name" value="{{ old('name', $provider?->name ?? auth()->user()->name) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control" name="phone" value="{{ old('phone', $provider?->phone ?? auth()->user()->phone) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="email" name="email" value="{{ old('email', $provider?->email ?? auth()->user()->email) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fiber category</label>
                                    <select class="form-select" name="category_id">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected((int) old('category_id', $provider?->category_id) === $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">County</label>
                                    <input class="form-control" name="county" value="{{ old('county', $provider?->county ?? auth()->user()->county) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Location</label>
                                    <input class="form-control" name="location" value="{{ old('location', $provider?->location ?? auth()->user()->location) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Availability</label>
                                    <select class="form-select" name="availability_status" required>
                                        @foreach(['available' => 'Available', 'busy' => 'Busy', 'offline' => 'Offline'] as $value => $label)
                                            <option value="{{ $value }}" @selected(old('availability_status', $provider?->availability_status ?? 'available') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Years of experience</label>
                                    <input class="form-control" type="number" min="0" max="60" name="experience_years" value="{{ old('experience_years', $provider?->experience_years) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Services</label>
                                    <select class="form-select" name="service_ids[]" multiple size="5">
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" @selected(in_array($service->id, $selectedServices, true))>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Fiber specialties</label>
                                    <textarea class="form-control" name="specialties" rows="3" required placeholder="Fiber splicing, OTDR testing, FTTH installation, structured cabling...">{{ old('specialties', $provider?->specialties) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Qualification summary</label>
                                    <textarea class="form-control" name="qualification_summary" rows="4" required placeholder="List certifications, training, licenses, completed projects, and relevant experience.">{{ old('qualification_summary', $provider?->qualification_summary) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Qualification document {{ $provider?->document_path ? '' : '(required)' }}</label>
                                    <input class="form-control" type="file" name="qualification_document" accept=".pdf,.jpg,.jpeg,.png" {{ $provider?->document_path ? '' : 'required' }}>
                                    @if($provider?->document_path)
                                        <div class="form-text">
                                            Current document:
                                            <a href="{{ route('media.image', ['path' => $provider->document_path]) }}" target="_blank" rel="noopener">View uploaded qualification</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <button class="btn btn-brand mt-4">{{ $provider ? 'Update Listing' : 'Submit Listing' }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card dashboard-content-card">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Review Status</h2>
                        <p class="mb-2"><strong>Status:</strong> <span class="status">{{ $provider?->verification_status ?? 'not submitted' }}</span></p>
                        <p class="text-muted mb-0">Approved technicians can be assigned provider work from bookings. Updating your listing sends it back for verification.</p>
                    </div>
                </div>
            </div>
        </div>
    @endisset
</div>
@endsection
