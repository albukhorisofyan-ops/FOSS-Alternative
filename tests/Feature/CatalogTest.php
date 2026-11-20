<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\FossAlternative;
use App\Models\ProprietarySoftware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $categoryDesign = Category::create([
            'name' => 'Desain Grafis',
            'slug' => 'desain-grafis',
        ]);

        $categoryOffice = Category::create([
            'name' => 'Perkantoran',
            'slug' => 'perkantoran',
        ]);

        $photoshop = ProprietarySoftware::create([
            'category_id' => $categoryDesign->id,
            'name' => 'Adobe Photoshop',
            'vendor' => 'Adobe Inc.',
            'price_per_year' => 3500000,
            'description' => 'Editor grafis raster standar industri.',
        ]);

        FossAlternative::create([
            'proprietary_software_id' => $photoshop->id,
            'name' => 'GIMP',
            'license' => 'GPL-3.0-or-later',
            'website' => 'https://www.gimp.org',
            'description' => 'GNU Image Manipulation Program.',
            'migration_guide' => 'Ekspor berkas PSD atau buka langsung di GIMP.',
        ]);

        $office = ProprietarySoftware::create([
            'category_id' => $categoryOffice->id,
            'name' => 'Microsoft 365',
            'vendor' => 'Microsoft',
            'price_per_year' => 1200000,
            'description' => 'Paket aplikasi perkantoran berbasis langganan.',
        ]);

        FossAlternative::create([
            'proprietary_software_id' => $office->id,
            'name' => 'LibreOffice',
            'license' => 'MPL-2.0',
            'website' => 'https://www.libreoffice.org',
            'description' => 'Paket aplikasi perkantoran open source lengkap.',
            'migration_guide' => 'Gunakan format OpenDocument (ODT, ODS) atau simpan sebagai DOCX/XLSX.',
        ]);
    }

    public function test_katalog_dapat_dibuka_dan_menampilkan_daftar_perangkat_lunak(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Adobe Photoshop');
        $response->assertSee('Microsoft 365');
        $response->assertSee('GIMP');
        $response->assertSee('LibreOffice');
        $response->assertSee('Desain Grafis');
        $response->assertSee('Perkantoran');
    }

    public function test_pencarian_nama_menampilkan_hasil_yang_benar(): void
    {
        $response = $this->get('/?q=photoshop');

        $response->assertStatus(200);
        $response->assertSee('Adobe Photoshop');
        $response->assertDontSee('Microsoft 365');
    }

    public function test_filter_kategori_berfungsi(): void
    {
        $officeCategory = Category::where('slug', 'perkantoran')->first();

        $response = $this->get('/?kategori=' . $officeCategory->id);

        $response->assertStatus(200);
        $response->assertSee('Microsoft 365');
        $response->assertDontSee('Adobe Photoshop');
    }

    public function test_kombinasi_pencarian_dan_filter_kategori(): void
    {
        $designCategory = Category::where('slug', 'desain-grafis')->first();

        // Cari Photoshop dalam kategori Desain Grafis (cocok)
        $responseMatch = $this->get('/?q=photoshop&kategori=' . $designCategory->id);
        $responseMatch->assertStatus(200);
        $responseMatch->assertSee('Adobe Photoshop');

        // Cari Photoshop dalam kategori Perkantoran (tidak cocok)
        $officeCategory = Category::where('slug', 'perkantoran')->first();
        $responseNoMatch = $this->get('/?q=photoshop&kategori=' . $officeCategory->id);
        $responseNoMatch->assertStatus(200);
        $responseNoMatch->assertSee('Tidak ada hasil.');
    }

    public function test_pencarian_tidak_ada_hasil_menampilkan_pesan(): void
    {
        $response = $this->get('/?q=randomstringtidakada123');

        $response->assertStatus(200);
        $response->assertSee('Tidak ada hasil.');
    }

    public function test_halaman_detail_menampilkan_informasi_lengkap_dan_alternatif(): void
    {
        $photoshop = ProprietarySoftware::where('name', 'Adobe Photoshop')->first();

        $response = $this->get('/software/' . $photoshop->id);

        $response->assertStatus(200);
        $response->assertSee('Adobe Photoshop');
        $response->assertSee('Adobe Inc.');
        $response->assertSee('3.500.000'); // format rupiah ribuan
        $response->assertSee('GIMP');
        $response->assertSee('Lisensi: GPL-3.0-or-later'); // lencana lisensi
        $response->assertSee('Ekspor berkas PSD atau buka langsung di GIMP.'); // panduan migrasi
        $response->assertSee('https://www.gimp.org');
        $response->assertSee('/kalkulator?software=' . $photoshop->id); // tombol hitung penghematan
    }

    public function test_detail_id_tidak_ada_menghasilkan_404(): void
    {
        $response = $this->get('/software/9999');

        $response->assertStatus(404);
    }
}
