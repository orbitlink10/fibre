@extends('layouts.dashboard')

@section('title', 'Fiber Tools')
@section('dashboard-title', 'Fiber Tools')
@section('dashboard-subtitle', 'Free interactive Fiber Optics Kenya tools listed on the homepage.')
@section('dashboard-actions')
    <a class="btn btn-primary rounded-pill fw-semibold px-4" href="{{ route('home') }}#fiber-tools-kenya" target="_blank" rel="noopener">View Homepage Section</a>
@endsection

@section('content')
<div class="px-3 pb-4">
    <div class="card dashboard-content-card overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted" style="letter-spacing:.16em;">Tool</th>
                        <th class="text-uppercase text-muted" style="letter-spacing:.16em;">URL</th>
                        <th class="text-uppercase text-muted" style="letter-spacing:.16em;">Summary</th>
                        <th class="text-uppercase text-muted" style="letter-spacing:.16em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tools as $tool)
                        <tr>
                            <td class="fw-semibold">{{ $tool['title'] }}</td>
                            <td><code>/{{ $tool['slug'] }}</code></td>
                            <td>{{ $tool['summary'] }}</td>
                            <td>
                                <a class="btn btn-sm btn-info text-white rounded-pill px-3" href="{{ url($tool['slug']) }}" target="_blank" rel="noopener">Preview</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
