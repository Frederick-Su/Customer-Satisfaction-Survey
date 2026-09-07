<?php

namespace Tests\Feature;

use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_browse_responses(): void
    {
        $this->get(route('admin.responses.index'))
            ->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_filter_responses(): void
    {
        $user = User::factory()->create();

        SurveyResponse::factory()->create([
            'nama' => 'Sari',
            'kepuasan_keseluruhan' => 5,
        ]);
        SurveyResponse::factory()->create([
            'nama' => 'Budi',
            'kepuasan_keseluruhan' => 2,
        ]);

        $this->actingAs($user)
            ->get(route('admin.responses.index', ['rating' => 5]))
            ->assertOk()
            ->assertSee('Sari')
            ->assertDontSee('Budi')
            ->assertSee('1')
            ->assertSee('respons ditemukan');
    }
}