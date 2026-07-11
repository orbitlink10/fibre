<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Provider;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TechnicianListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_must_sign_in_to_access_technicians_page(): void
    {
        $this->get('/technicians')->assertRedirect('/login');
    }

    public function test_signed_in_user_can_submit_technician_listing_with_qualification_document(): void
    {
        Storage::fake('public');

        $customerRole = Role::create(['name' => 'customer', 'label' => 'Customer']);
        Role::create(['name' => 'provider', 'label' => 'Service Provider']);

        $user = User::factory()->create([
            'role_id' => $customerRole->id,
            'phone' => '0712345678',
            'county' => 'Nairobi',
            'location' => 'Westlands',
        ]);

        $category = Category::create([
            'name' => 'Fiber Optic Installation',
            'slug' => 'fiber-optic-installation',
            'is_active' => true,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Fiber Splicing',
            'slug' => 'fiber-splicing',
            'description' => 'Fiber optic splicing and testing.',
            'base_price' => 0,
            'unit_type' => 'fixed',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/technicians', [
            'name' => 'Jane Fiber Tech',
            'phone' => '0712345678',
            'email' => 'jane@example.com',
            'category_id' => $category->id,
            'service_ids' => [$service->id],
            'county' => 'Nairobi',
            'location' => 'Westlands',
            'availability_status' => 'available',
            'experience_years' => 4,
            'specialties' => 'Fiber splicing, OTDR testing, FTTH installation',
            'qualification_summary' => 'Certified fiber technician with FTTH installation experience.',
            'qualification_document' => UploadedFile::fake()->create('qualification.pdf', 128, 'application/pdf'),
        ]);

        $response->assertRedirect(route('technicians.index'));

        $provider = Provider::firstOrFail();

        $this->assertSame($user->id, $provider->user_id);
        $this->assertSame('pending', $provider->verification_status);
        $this->assertSame('Fiber splicing, OTDR testing, FTTH installation', $provider->specialties);
        $this->assertTrue($provider->services->contains($service));
        Storage::disk('public')->assertExists($provider->document_path);
        $this->assertSame('provider', $user->refresh()->role->name);
    }
}
