<?php

namespace Tests\Feature;

use App\Models\PlaceholderPost;
use App\Models\PlaceholderUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_requires_basic_authentication(): void
    {
        $response = $this->getJson('/api/v1/posts');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_api_data(): void
    {
        User::factory()->create([
            'email' => 'api@example.com',
            'password' => 'password',
        ]);

        $placeholderUser = PlaceholderUser::query()->create([
            'external_id' => 1,
            'name' => 'Leanne Graham',
            'username' => 'Bret',
            'email' => 'leanne@example.com',
            'phone' => '1-770-736-8031',
            'website' => 'hildegard.org',
        ]);

        PlaceholderPost::query()->create([
            'external_id' => 1,
            'placeholder_user_id' => $placeholderUser->id,
            'title' => 'Sample Post',
            'body' => 'Sample Body',
        ]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Basic '.base64_encode('api@example.com:password'),
            ])
            ->getJson('/api/v1/posts');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.external_id', 1)
            ->assertJsonPath('data.0.title', 'Sample Post');
    }
}
