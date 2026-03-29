<?php

namespace App\Services;

use App\Models\PlaceholderAlbum;
use App\Models\PlaceholderComment;
use App\Models\PlaceholderPhoto;
use App\Models\PlaceholderPost;
use App\Models\PlaceholderTodo;
use App\Models\PlaceholderUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class JsonPlaceholderSyncService
{
    /**
     * @return array<string, int>
     */
    public function sync(bool $truncate = false): array
    {
        $now = now();

        $payload = $this->fetchPayload();

        return DB::transaction(function () use ($truncate, $payload, $now): array {
            if ($truncate) {
                $this->clearExistingData();
            }

            $userRows = collect($payload['users'])
                ->map(fn (array $user): array => [
                    'external_id' => $user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'phone' => $user['phone'] ?? null,
                    'website' => $user['website'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            PlaceholderUser::query()->upsert(
                $userRows,
                ['external_id'],
                ['name', 'username', 'email', 'phone', 'website', 'updated_at']
            );

            $userMap = PlaceholderUser::query()
                ->pluck('id', 'external_id')
                ->map(fn ($id): int => (int) $id)
                ->all();

            $addressRows = collect($payload['users'])
                ->filter(fn (array $user): bool => isset($userMap[$user['id']]))
                ->map(fn (array $user): array => [
                    'placeholder_user_id' => $userMap[$user['id']],
                    'street' => data_get($user, 'address.street'),
                    'suite' => data_get($user, 'address.suite'),
                    'city' => data_get($user, 'address.city'),
                    'zipcode' => data_get($user, 'address.zipcode'),
                    'lat' => data_get($user, 'address.geo.lat'),
                    'lng' => data_get($user, 'address.geo.lng'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            DB::table('placeholder_addresses')->upsert(
                $addressRows,
                ['placeholder_user_id'],
                ['street', 'suite', 'city', 'zipcode', 'lat', 'lng', 'updated_at']
            );

            $companyRows = collect($payload['users'])
                ->filter(fn (array $user): bool => isset($userMap[$user['id']]))
                ->map(fn (array $user): array => [
                    'placeholder_user_id' => $userMap[$user['id']],
                    'name' => data_get($user, 'company.name'),
                    'catch_phrase' => data_get($user, 'company.catchPhrase'),
                    'bs' => data_get($user, 'company.bs'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            DB::table('placeholder_companies')->upsert(
                $companyRows,
                ['placeholder_user_id'],
                ['name', 'catch_phrase', 'bs', 'updated_at']
            );

            $postRows = collect($payload['posts'])
                ->filter(fn (array $post): bool => isset($userMap[$post['userId']]))
                ->map(fn (array $post): array => [
                    'external_id' => $post['id'],
                    'placeholder_user_id' => $userMap[$post['userId']],
                    'title' => $post['title'],
                    'body' => $post['body'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            PlaceholderPost::query()->upsert(
                $postRows,
                ['external_id'],
                ['placeholder_user_id', 'title', 'body', 'updated_at']
            );

            $postMap = PlaceholderPost::query()
                ->pluck('id', 'external_id')
                ->map(fn ($id): int => (int) $id)
                ->all();

            $commentRows = collect($payload['comments'])
                ->filter(fn (array $comment): bool => isset($postMap[$comment['postId']]))
                ->map(fn (array $comment): array => [
                    'external_id' => $comment['id'],
                    'placeholder_post_id' => $postMap[$comment['postId']],
                    'name' => $comment['name'],
                    'email' => $comment['email'],
                    'body' => $comment['body'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            PlaceholderComment::query()->upsert(
                $commentRows,
                ['external_id'],
                ['placeholder_post_id', 'name', 'email', 'body', 'updated_at']
            );

            $albumRows = collect($payload['albums'])
                ->filter(fn (array $album): bool => isset($userMap[$album['userId']]))
                ->map(fn (array $album): array => [
                    'external_id' => $album['id'],
                    'placeholder_user_id' => $userMap[$album['userId']],
                    'title' => $album['title'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            PlaceholderAlbum::query()->upsert(
                $albumRows,
                ['external_id'],
                ['placeholder_user_id', 'title', 'updated_at']
            );

            $albumMap = PlaceholderAlbum::query()
                ->pluck('id', 'external_id')
                ->map(fn ($id): int => (int) $id)
                ->all();

            $photoRows = collect($payload['photos'])
                ->filter(fn (array $photo): bool => isset($albumMap[$photo['albumId']]))
                ->map(fn (array $photo): array => [
                    'external_id' => $photo['id'],
                    'placeholder_album_id' => $albumMap[$photo['albumId']],
                    'title' => $photo['title'],
                    'url' => $photo['url'],
                    'thumbnail_url' => $photo['thumbnailUrl'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            PlaceholderPhoto::query()->upsert(
                $photoRows,
                ['external_id'],
                ['placeholder_album_id', 'title', 'url', 'thumbnail_url', 'updated_at']
            );

            $todoRows = collect($payload['todos'])
                ->filter(fn (array $todo): bool => isset($userMap[$todo['userId']]))
                ->map(fn (array $todo): array => [
                    'external_id' => $todo['id'],
                    'placeholder_user_id' => $userMap[$todo['userId']],
                    'title' => $todo['title'],
                    'completed' => (bool) $todo['completed'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

            PlaceholderTodo::query()->upsert(
                $todoRows,
                ['external_id'],
                ['placeholder_user_id', 'title', 'completed', 'updated_at']
            );

            return [
                'users' => PlaceholderUser::query()->count(),
                'posts' => PlaceholderPost::query()->count(),
                'comments' => PlaceholderComment::query()->count(),
                'albums' => PlaceholderAlbum::query()->count(),
                'photos' => PlaceholderPhoto::query()->count(),
                'todos' => PlaceholderTodo::query()->count(),
            ];
        });
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function fetchPayload(): array
    {
        $client = Http::baseUrl(config('services.jsonplaceholder.base_url'))
            ->acceptJson()
            ->timeout(30)
            ->retry(2, 200);

        return [
            'users' => $client->get('/users')->throw()->json(),
            'posts' => $client->get('/posts')->throw()->json(),
            'comments' => $client->get('/comments')->throw()->json(),
            'albums' => $client->get('/albums')->throw()->json(),
            'photos' => $client->get('/photos')->throw()->json(),
            'todos' => $client->get('/todos')->throw()->json(),
        ];
    }

    private function clearExistingData(): void
    {
        PlaceholderComment::query()->delete();
        PlaceholderPhoto::query()->delete();
        PlaceholderPost::query()->delete();
        PlaceholderAlbum::query()->delete();
        PlaceholderTodo::query()->delete();
        DB::table('placeholder_companies')->delete();
        DB::table('placeholder_addresses')->delete();
        PlaceholderUser::query()->delete();
    }
}
