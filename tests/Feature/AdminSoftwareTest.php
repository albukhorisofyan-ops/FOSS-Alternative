<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ProprietarySoftware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSoftwareTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->category = Category::create(['name' => 'Office', 'slug' => 'office']);
    }

    public function test_guest_cannot_access_software_management(): void
    {
        $this->get('/admin/software')->assertRedirect('/login');
        $this->get('/admin/software/create')->assertRedirect('/login');
    }

    public function test_admin_can_see_software_list(): void
    {
        ProprietarySoftware::create([
            'category_id' => $this->category->id,
            'name' => 'Microsoft 365',
            'vendor' => 'Microsoft',
            'price_per_year' => 1800000,
        ]);

        $this->actingAs($this->admin)
            ->get('/admin/software')
            ->assertOk()
            ->assertSee('Microsoft 365')
            ->assertSee('Rp 1.800.000');
    }

    public function test_admin_can_create_software(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/software', [
            'category_id' => $this->category->id,
            'name' => 'Adobe Photoshop',
            'vendor' => 'Adobe',
            'price_per_year' => 1300000,
            'description' => 'Editor gambar raster.',
        ]);

        $response->assertRedirect(route('admin.software.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('proprietary_software', [
            'name' => 'Adobe Photoshop',
            'category_id' => $this->category->id,
            'price_per_year' => 1300000,
        ]);
    }

    public function test_invalid_input_is_rejected_and_old_values_are_kept(): void
    {
        $response = $this->actingAs($this->admin)
            ->from('/admin/software/create')
            ->post('/admin/software', [
                'category_id' => 9999,
                'name' => '',
                'price_per_year' => -1,
            ]);

        $response->assertRedirect('/admin/software/create');
        $response->assertSessionHasErrors(['category_id', 'name', 'price_per_year']);
        $this->assertNull(session()->getOldInput('name'));
        $this->assertSame(-1, session()->getOldInput('price_per_year'));

        $this->assertDatabaseCount('proprietary_software', 0);
    }

    public function test_admin_can_update_software(): void
    {
        $software = ProprietarySoftware::create([
            'category_id' => $this->category->id,
            'name' => 'Nama Lama',
            'price_per_year' => 1000,
        ]);

        $response = $this->actingAs($this->admin)->put('/admin/software/'.$software->id, [
            'category_id' => $this->category->id,
            'name' => 'Nama Baru',
            'vendor' => null,
            'price_per_year' => 2500,
            'description' => null,
        ]);

        $response->assertRedirect(route('admin.software.index'));

        $this->assertDatabaseHas('proprietary_software', [
            'id' => $software->id,
            'name' => 'Nama Baru',
            'price_per_year' => 2500,
        ]);
    }

    public function test_admin_can_delete_software(): void
    {
        $software = ProprietarySoftware::create([
            'category_id' => $this->category->id,
            'name' => 'Akan Dihapus',
            'price_per_year' => 0,
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/software/'.$software->id);

        $response->assertRedirect(route('admin.software.index'));
        $this->assertDatabaseMissing('proprietary_software', ['id' => $software->id]);
    }
}
