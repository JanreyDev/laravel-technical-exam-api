<?php

namespace App\Console\Commands;

use App\Services\JsonPlaceholderSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncJsonPlaceholderData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:jsonplaceholder {--truncate : Delete existing imported data before syncing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch JSONPlaceholder data and store it using Eloquent';

    /**
     * Execute the console command.
     */
    public function handle(JsonPlaceholderSyncService $syncService): int
    {
        $this->info('Sync started...');

        try {
            $counts = $syncService->sync((bool) $this->option('truncate'));
        } catch (Throwable $exception) {
            $this->error('Sync failed: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Entity', 'Rows in database'],
            collect($counts)->map(fn (int $count, string $entity): array => [$entity, $count])->values()->all()
        );

        $this->info('Sync completed successfully.');

        return self::SUCCESS;
    }
}
