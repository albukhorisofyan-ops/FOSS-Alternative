<?php

namespace Tests\Feature;

use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_moderation(): void
    {
        $this->get('/admin/suggestions')->assertRedirect('/login');

        $this->patch('/admin/suggestions/1', ['status' => 'approved'])->assertRedirect('/login');
    }

    public function test_admin_can_see_suggestions(): void
    {
        $this->createSuggestion();

        $this->actingAs(User::factory()->create())
            ->get('/admin/suggestions')
            ->assertOk()
            ->assertSee('GIMP')
            ->assertSee('Adobe Photoshop')
            ->assertSee('Menunggu');
    }

    public function test_admin_can_approve_suggestion(): void
    {
        $suggestion = $this->createSuggestion();

        $response = $this->actingAs(User::factory()->create())
            ->patch('/admin/suggestions/'.$suggestion->id, ['status' => 'approved']);

        $response->assertRedirect(route('admin.suggestions.index'));
        $response->assertSessionHas('status');
        $this->assertSame('approved', $suggestion->fresh()->status);
    }

    public function test_admin_can_reject_suggestion(): void
    {
        $suggestion = $this->createSuggestion();

        $response = $this->actingAs(User::factory()->create())
            ->patch('/admin/suggestions/'.$suggestion->id, ['status' => 'rejected']);

        $response->assertRedirect(route('admin.suggestions.index'));
        $this->assertSame('rejected', $suggestion->fresh()->status);
    }

    public function test_status_must_be_approved_or_rejected(): void
    {
        $suggestion = $this->createSuggestion();

        $response = $this->actingAs(User::factory()->create())
            ->from('/admin/suggestions')
            ->patch('/admin/suggestions/'.$suggestion->id, ['status' => 'pending']);

        $response->assertRedirect('/admin/suggestions');
        $response->assertSessionHasErrors('status');
        $this->assertSame('pending', $suggestion->fresh()->status);
    }

    private function createSuggestion(): Suggestion
    {
        return Suggestion::create([
            'name' => 'GIMP',
            'proprietary_name' => 'Adobe Photoshop',
            'website' => 'https://www.gimp.org',
            'note' => 'Alternatif gratis.',
            'status' => 'pending',
        ]);
    }
}
