<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ProprietarySoftware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TcoCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private ProprietarySoftware $software;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Office & Produktivitas',
            'slug' => 'office-produktivitas',
        ]);

        $this->software = ProprietarySoftware::create([
            'category_id' => $category->id,
            'name' => 'Microsoft 365 Business Standard',
            'vendor' => 'Microsoft',
            'price_per_year' => 1500000,
            'description' => 'Paket aplikasi produktivitas berbasis langganan.',
        ]);
    }

    public function test_halaman_kalkulator_dapat_dibuka(): void
    {
        $response = $this->get('/kalkulator');

        $response->assertOk();
        $response->assertSee('Kalkulator Penghematan');
        $response->assertSee('Microsoft 365 Business Standard');
    }

    public function test_perangkat_lunak_terpilih_otomatis_dari_parameter_software(): void
    {
        $response = $this->get('/kalkulator?software='.$this->software->id);

        $response->assertOk();
        $response->assertSee('value="'.$this->software->id.'"', false);
        $response->assertSee('selected', false);
    }

    public function test_perhitungan_penghematan_sesuai_rumus(): void
    {
        $response = $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 100,
            'jumlah_tahun' => 3,
            'biaya_migrasi' => 0,
        ]);

        $response->assertOk();
        $response->assertSee('Rp 450.000.000');
        $response->assertSee('Rp 0');
        $response->assertSee('value="100"', false);
        $response->assertSee('value="3"', false);
    }

    public function test_biaya_migrasi_mengurangi_penghematan(): void
    {
        $response = $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 100,
            'jumlah_tahun' => 3,
            'biaya_migrasi' => 10000000,
        ]);

        $response->assertOk();
        $response->assertSee('Rp 10.000.000');
        $response->assertSee('Rp 440.000.000');
    }

    public function test_biaya_migrasi_opsional_dianggap_nol(): void
    {
        $response = $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 1,
            'jumlah_tahun' => 1,
        ]);

        $response->assertOk();
        $response->assertSee('Rp 1.500.000');
        $response->assertSee('Rp 0');
    }

    public function test_skenario_tanpa_penghematan_ditampilkan(): void
    {
        $response = $this->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 1,
            'jumlah_tahun' => 1,
            'biaya_migrasi' => 2000000,
        ]);

        $response->assertOk();
        $response->assertSee('Pada skenario ini belum ada penghematan.');
        $response->assertSee('500.000');
    }

    public function test_pesan_galat_validasi_tampil_pada_formulir(): void
    {
        $response = $this->from('/kalkulator')->followingRedirects()->post('/kalkulator', [
            'software_id' => $this->software->id,
            'jumlah_pc' => 0,
            'jumlah_tahun' => 3,
            'biaya_migrasi' => 0,
        ]);

        $response->assertOk();
        $response->assertSee('Jumlah PC minimal 1.');
        $response->assertSee('value="0"', false);
        $response->assertSee('value="3"', false);
    }

    public function test_input_tidak_valid_ditolak(): void
    {
        $base = [
            'software_id' => $this->software->id,
            'jumlah_pc' => 10,
            'jumlah_tahun' => 3,
            'biaya_migrasi' => 0,
        ];

        $cases = [
            ['jumlah_pc' => '', 'field' => 'jumlah_pc'],
            ['jumlah_pc' => 0, 'field' => 'jumlah_pc'],
            ['jumlah_pc' => 100001, 'field' => 'jumlah_pc'],
            ['jumlah_pc' => 'sepuluh', 'field' => 'jumlah_pc'],
            ['jumlah_tahun' => 0, 'field' => 'jumlah_tahun'],
            ['jumlah_tahun' => 11, 'field' => 'jumlah_tahun'],
            ['jumlah_tahun' => 1.5, 'field' => 'jumlah_tahun'],
            ['software_id' => 9999, 'field' => 'software_id'],
            ['software_id' => '', 'field' => 'software_id'],
            ['biaya_migrasi' => -1, 'field' => 'biaya_migrasi'],
            ['biaya_migrasi' => 'gratis', 'field' => 'biaya_migrasi'],
        ];

        foreach ($cases as $case) {
            $field = $case['field'];
            unset($case['field']);

            $this->post('/kalkulator', array_merge($base, $case))
                ->assertSessionHasErrors($field);
        }
    }
}
