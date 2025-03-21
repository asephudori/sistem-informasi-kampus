<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class DeleteUnusedTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:delete-unused';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tokens that have not been used for 14 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subDays(14);

        // Hapus token yang `last_used_at` lebih dari 14 hari lalu
        $deleted = PersonalAccessToken::where('last_used_at', '<', $threshold)->delete();

        // Hapus token yang tidak pernah digunakan (`last_used_at` masih null) dan dibuat lebih dari 14 hari lalu
        $deleted += PersonalAccessToken::whereNull('last_used_at')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("Deleted $deleted unused tokens.");
    }
}
