<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Provider;
use App\Models\Role;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechnicianController extends Controller
{
    public function publicIndex()
    {
        return view('technicians.public', [
            'technicians' => Provider::with(['category', 'services'])
                ->where('verification_status', 'approved')
                ->where('availability_status', '!=', 'offline')
                ->orderByDesc('rating')
                ->latest()
                ->paginate(12),
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isRole(['admin', 'dispatcher'])) {
            return view('technicians.index', [
                'technicians' => Provider::with(['category', 'services', 'user'])->latest()->paginate(20),
            ]);
        }

        return view('technicians.index', [
            'provider' => $user->provider?->load('services'),
            'categories' => $this->fiberCategories(),
            'services' => $this->fiberServices(),
        ]);
    }

    public function store(Request $request)
    {
        return $this->saveTechnician($request);
    }

    public function update(Request $request)
    {
        return $this->saveTechnician($request);
    }

    private function saveTechnician(Request $request)
    {
        $user = $request->user();
        $provider = $user->provider;

        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'exists:services,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'county' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:255'],
            'specialties' => ['required', 'string', 'max:1000'],
            'qualification_summary' => ['required', 'string', 'max:2000'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'availability_status' => ['required', Rule::in(['available', 'busy', 'offline'])],
            'qualification_document' => [
                $provider?->document_path ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:8192',
            ],
        ]);

        $serviceIds = $data['service_ids'] ?? [];
        unset($data['service_ids'], $data['qualification_document']);

        $data['user_id'] = $user->id;
        $data['email'] = $data['email'] ?: $user->email;
        $data['verification_status'] = 'pending';

        if ($request->hasFile('qualification_document')) {
            $data['document_path'] = $request->file('qualification_document')
                ->store('provider-documents', 'public');
        }

        $provider = Provider::updateOrCreate(['user_id' => $user->id], $data);
        $provider->services()->sync($serviceIds);

        if (! $user->isRole(['admin', 'dispatcher'])) {
            $providerRole = Role::firstOrCreate(
                ['name' => 'provider'],
                ['label' => 'Service Provider']
            );

            $user->update(['role_id' => $providerRole->id]);
        }

        return redirect()
            ->route('technicians.index')
            ->with('success', 'Technician listing submitted for verification.');
    }

    private function fiberCategories()
    {
        $terms = ['fiber', 'fibre', 'optic', 'cable', 'network', 'wi-fi', 'wifi', 'internet', 'cctv', 'security'];

        $categories = Category::query()
            ->where('is_active', true)
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('name', 'like', '%'.$term.'%')
                        ->orWhere('slug', 'like', '%'.$term.'%');
                }
            })
            ->orderBy('name')
            ->get();

        return $categories->isNotEmpty()
            ? $categories
            : Category::where('is_active', true)->orderBy('name')->get();
    }

    private function fiberServices()
    {
        $terms = ['fiber', 'fibre', 'optic', 'splicing', 'network', 'wi-fi', 'wifi', 'internet', 'cctv', 'security'];

        $services = Service::query()
            ->where('is_active', true)
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('name', 'like', '%'.$term.'%')
                        ->orWhere('description', 'like', '%'.$term.'%');
                }
            })
            ->orderBy('name')
            ->get();

        return $services->isNotEmpty()
            ? $services
            : Service::where('is_active', true)->orderBy('name')->get();
    }
}
