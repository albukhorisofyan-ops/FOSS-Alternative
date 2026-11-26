<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ProprietarySoftware;
use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuggestionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Desain Grafis',
            'slug' => 'desain-grafis',
        ]);

        ProprietarySoftware::create([
            'category_id' => $category->id,
            'name' => 'Adobe Photoshop',
            'vendor' => 'Adobe',
            'price_per_year' => 3500000,
        ]);
    }

    public function test_guest_can_open_suggestion_form(): void
    {
        $this->get('/saran')
            ->assertOk()
            ->assertSee('Usulkan Alternatif Open Source');
    }

    public function test_guest_can_submit_suggestion(): void
    {
        $response = $this->post('/saran', [
            'name' => 'GIMP',
            'proprietary_name' => 'Adobe Photoshop',
            'website' => 'https://www.gimp.org',
            'note' => 'Alternatif gratis untuk penyuntingan foto.',
        ]);

        $response->assertRedirect(route('suggestions.create'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('suggestions', [
            'name' => 'GIMP',
            'proprietary_name' => 'Adobe Photoshop',
            'website' => 'https://www.gimp.org',
            'status' => 'pending',
        ]);
    }

    public function test_public_form_cannot_set_status(): void
    {
        $this->post('/saran', [
            'name' => 'GIMP',
            'proprietary_name' => 'Adobe Photoshop',
            'status' => 'approved',
        ])->assertRedirect(route('suggestions.create'));

        $this->assertSame('pending', Suggestion::firstOrFail()->status);
    }

    public function test_website_must_be_a_valid_url(): void
    {
        $response = $this->from('/saran')->post('/saran', [
            'name' => 'GIMP',
            'proprietary_name' => 'Adobe Photoshop',
            'website' => 'bukan-url',
        ]);

        $response->assertRedirect('/saran');
        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount('suggestions', 0);
    }

    public function test_required_fields_are_validated(): void
    {
        $response = $this->from('/saran')->post('/saran', [
            'note' => 'Tanpa nama alternatif dan perangkat lunak.',
        ]);

        $response->assertSessionHasErrors(['name', 'proprietary_name']);
        $this->assertDatabaseCount('suggestions', 0);
    }
}
