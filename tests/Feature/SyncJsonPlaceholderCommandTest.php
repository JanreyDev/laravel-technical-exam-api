<?php

namespace Tests\Feature;

use App\Models\PlaceholderPost;
use App\Models\PlaceholderUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncJsonPlaceholderCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_jsonplaceholder_data_to_database(): void
    {
        Http::fake([
            'https://jsonplaceholder.typicode.com/users' => Http::response([
                [
                    'id' => 1,
                    'name' => 'Leanne Graham',
                    'username' => 'Bret',
                    'email' => 'leanne@example.com',
                    'phone' => '1-770-736-8031',
                    'website' => 'hildegard.org',
                    'address' => [
                        'street' => 'Kulas Light',
                        'suite' => 'Apt. 556',
                        'city' => 'Gwenborough',
                        'zipcode' => '92998-3874',
                        'geo' => ['lat' => '-37.3159', 'lng' => '81.1496'],
                    ],
                    'company' => [
                        'name' => 'Romaguera-Crona',
                        'catchPhrase' => 'Multi-layered client-server neural-net',
                        'bs' => 'harness real-time e-markets',
                    ],
                ],
            ]),
            'https://jsonplaceholder.typicode.com/posts' => Http::response([
                ['id' => 1, 'userId' => 1, 'title' => 'Post title', 'body' => 'Post body'],
            ]),
            'https://jsonplaceholder.typicode.com/comments' => Http::response([
                ['id' => 1, 'postId' => 1, 'name' => 'Comment', 'email' => 'comment@example.com', 'body' => 'Comment body'],
            ]),
            'https://jsonplaceholder.typicode.com/albums' => Http::response([
                ['id' => 1, 'userId' => 1, 'title' => 'Album title'],
            ]),
            'https://jsonplaceholder.typicode.com/photos' => Http::response([
                ['id' => 1, 'albumId' => 1, 'title' => 'Photo title', 'url' => 'https://example.com/photo.jpg', 'thumbnailUrl' => 'https://example.com/thumb.jpg'],
            ]),
            'https://jsonplaceholder.typicode.com/todos' => Http::response([
                ['id' => 1, 'userId' => 1, 'title' => 'Todo title', 'completed' => true],
            ]),
        ]);

        $this->artisan('sync:jsonplaceholder')
            ->expectsOutput('Sync started...')
            ->expectsOutput('Sync completed successfully.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('placeholder_users', 1);
        $this->assertDatabaseCount('placeholder_posts', 1);
        $this->assertDatabaseCount('placeholder_comments', 1);
        $this->assertDatabaseCount('placeholder_albums', 1);
        $this->assertDatabaseCount('placeholder_photos', 1);
        $this->assertDatabaseCount('placeholder_todos', 1);

        $this->assertTrue(
            PlaceholderUser::query()->where('external_id', 1)->exists()
        );

        $this->assertTrue(
            PlaceholderPost::query()->where('external_id', 1)->exists()
        );
    }
}
