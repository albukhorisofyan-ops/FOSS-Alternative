<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ProprietarySoftware;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private ProprietarySoftware $software;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Desain Grafis',
            'slug' => 'desain-grafis',
        ]);

        $this->software = ProprietarySoftware::create([
            'category_id' => $this->category->id,
            'name' => 'Adobe Photoshop',
            'vendor' => 'Adobe',
            'price_per_year' => 1500000,
            'description' => 'Editor grafis.',
        ]);

        $this->admin = User::create([
            'name' => 'Admin AlterFOSS',
            'email' => 'admin@alterfoss.test',
            'password' => 'password',
        ]);
    }

    public function test_kalkulator_tco_dapat_dibuka_dan_menghitung_penghematan(): void
    {
        $this->get('/kalkulator?software=' . $this->software->id)
            ->assertStatus(200)
            ->assertSee('Adobe Photoshop');

        // 1.500.000 * 100 PC * 3 tahun - 0 = 450.000.000
        $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 100,
            'jumlah_tahun' => 3,
            'biaya_migrasi' => 0,
        ])
            ->assertStatus(200)
            ->assertSee('450.000.000');
    }

    public function test_kalkulator_tco_menangani_penghematan_negatif_dan_validasi(): void
    {
        $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 1,
            'jumlah_tahun' => 1,
            'biaya_migrasi' => 5000000,
        ])
            ->assertStatus(200)
            ->assertSee('Pada skenario ini belum ada penghematan.');

        $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 0,
            'jumlah_tahun' => 11,
        ])->assertSessionHasErrors(['jumlah_pc', 'jumlah_tahun']);
    }

    public function test_login_dan_logout_berfungsi(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertDontSee('href="/login"', false);

        $this->get('/login')->assertStatus(200);

        $this->post('/login', [
            'email' => 'admin@alterfoss.test',
            'password' => 'salah',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => 'admin@alterfoss.test',
            'password' => 'password',
        ])->assertRedirect('/admin/software');

        $this->assertAuthenticatedAs($this->admin);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_halaman_admin_terkunci_untuk_tamu(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/software')->assertRedirect('/login');
        $this->get('/admin/suggestions')->assertRedirect('/login');
    }

    public function test_pengunjung_dapat_mengirim_usulan_dan_admin_dapat_moderasi(): void
    {
        $this->get('/saran')
            ->assertStatus(200)
            ->assertSee('Adobe Photoshop');

        $this->post('/saran', [
            'name' => 'Krita',
            'proprietary_name' => 'Software Tidak Ada Di Katalog',
        ])->assertSessionHasErrors('proprietary_name');

        $this->post('/saran', [
            'name' => 'Krita',
            'proprietary_name' => 'Adobe Photoshop',
            'website' => 'https://krita.org',
            'note' => 'Alternatif lukis digital gratis.',
        ])->assertRedirect('/saran');

        $suggestion = Suggestion::where('name', 'Krita')->firstOrFail();
        $this->assertSame('pending', $suggestion->status);

        $this->actingAs($this->admin);

        $this->get('/admin/suggestions')
            ->assertStatus(200)
            ->assertSee('Krita');

        $this->patch('/admin/suggestions/' . $suggestion->id, [
            'status' => 'approved',
        ])->assertRedirect('/admin/suggestions');

        $this->assertSame('approved', $suggestion->fresh()->status);

        // Usulan yang sudah dimoderasi tidak lagi tampil di daftar usulan
        $this->get('/admin/suggestions')
            ->assertStatus(200)
            ->assertSee('Belum ada usulan alternatif yang masuk.');

        // Tidak dapat diubah lagi setelah dimoderasi
        $this->patch('/admin/suggestions/' . $suggestion->id, [
            'status' => 'rejected',
        ])->assertRedirect('/admin/suggestions')
            ->assertSessionHas('error');

        $this->assertSame('approved', $suggestion->fresh()->status);

        $pendingSuggestion = Suggestion::create([
            'name' => 'GIMP',
            'proprietary_name' => 'Photoshop',
            'status' => 'pending',
        ]);

        $this->patch('/admin/suggestions/' . $pendingSuggestion->id, [
            'status' => 'invalid_status',
        ])->assertSessionHasErrors('status');
    }

    public function test_admin_crud_software_berbayar(): void
    {
        $this->actingAs($this->admin);

        $this->get('/admin')->assertRedirect('/admin/software');
        $this->get('/admin/software')->assertStatus(200)->assertSee('Adobe Photoshop');
        $this->get('/admin/software/create')->assertStatus(200);

        $this->post('/admin/software', [
            'category_id' => $this->category->id,
            'name' => 'CorelDRAW',
            'vendor' => 'Corel',
            'price_per_year' => 2500000,
            'description' => 'Desain vektor.',
        ])->assertRedirect('/admin/software');

        $corel = ProprietarySoftware::where('name', 'CorelDRAW')->firstOrFail();

        $this->get('/admin/software/' . $corel->id . '/edit')->assertStatus(200);

        $this->put('/admin/software/' . $corel->id, [
            'category_id' => $this->category->id,
            'name' => 'CorelDRAW Graphics Suite',
            'vendor' => 'Corel',
            'price_per_year' => 3000000,
        ])->assertRedirect('/admin/software');

        $this->assertSame('CorelDRAW Graphics Suite', $corel->fresh()->name);

        $this->delete('/admin/software/' . $corel->id)->assertRedirect('/admin/software');
        $this->assertDatabaseMissing('proprietary_software', ['id' => $corel->id]);
    }
}
