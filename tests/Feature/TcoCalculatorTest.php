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
        $response->assertSee('Kalkulator Penghematan Biaya');
        $response->assertSee('Microsoft 365 Business Standard');
    }

    public function test_perangkat_lunak_terpilih_otomatis_dari_parameter_software(): void
    {
        $response = $this->get('/kalkulator?software='.$this->software->id);

        $response->assertOk();
        $response->assertSee('value="'.$this->software->id.'" selected', false);
    }

    public function test_perhitungan_penghematan_sesuai_rumus(): void
    {
        $response = $this->post('/kalkulator', [
            'software' => $this->software->id,
            'pc' => 100,
            'years' => 3,
            'migration_cost' => 0,
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
            'software' => $this->software->id,
            'pc' => 100,
            'years' => 3,
            'migration_cost' => 10000000,
        ]);

        $response->assertOk();
        $response->assertSee('Rp 10.000.000');
        $response->assertSee('Rp 440.000.000');
    }

    public function test_biaya_migrasi_opsional_dianggap_nol(): void
    {
        $response = $this->post('/kalkulator', [
            'software' => $this->software->id,
            'pc' => 1,
            'years' => 1,
        ]);

        $response->assertOk();
        $response->assertSee('Rp 1.500.000');
        $response->assertSee('Rp 0');
    }

    public function test_skenario_tanpa_penghematan_ditampilkan(): void
    {
        $response = $this->post('/kalkulator', [
            'software' => $this->software->id,
            'pc' => 1,
            'years' => 1,
            'migration_cost' => 2000000,
        ]);

        $response->assertOk();
        $response->assertSee('Pada skenario ini belum ada penghematan.');
        $response->assertSee('Rp -500.000');
    }

    public function test_pesan_galat_validasi_tampil_pada_formulir(): void
    {
        $response = $this->followingRedirects()->post('/kalkulator', [
            'software' => $this->software->id,
            'pc' => 0,
            'years' => 3,
            'migration_cost' => 0,
        ]);

        $response->assertOk();
        $response->assertSee('Jumlah PC harus antara 1 sampai 100.000.');
        $response->assertSee('value="0"', false);
        $response->assertSee('value="3"', false);
    }

    public function test_input_tidak_valid_ditolak(): void
    {
        $base = [
            'software' => $this->software->id,
            'pc' => 10,
            'years' => 3,
            'migration_cost' => 0,
        ];

        $cases = [
            ['pc' => '', 'field' => 'pc'],
            ['pc' => 0, 'field' => 'pc'],
            ['pc' => 100001, 'field' => 'pc'],
            ['pc' => 'sepuluh', 'field' => 'pc'],
            ['years' => 0, 'field' => 'years'],
            ['years' => 11, 'field' => 'years'],
            ['years' => 1.5, 'field' => 'years'],
            ['software' => 9999, 'field' => 'software'],
            ['software' => '', 'field' => 'software'],
            ['migration_cost' => -1, 'field' => 'migration_cost'],
            ['migration_cost' => 'gratis', 'field' => 'migration_cost'],
        ];

        foreach ($cases as $case) {
            $field = $case['field'];
            unset($case['field']);

            $this->post('/kalkulator', array_merge($base, $case))
                ->assertSessionHasErrors($field);
        }
    }
}
