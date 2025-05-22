<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PublicCleaner extends Command
{
    protected $signature = 'public:clear';
    protected $description = 'Delete public files older than 30 days';

    public function handle()
    {
        $get_path_lists = DB::table('public_cleaners')
        ->where('remark', 1)
        ->get();

        $path_lists = $get_path_lists->pluck('path')->toArray();

        if (empty($path_lists)) {
            $this->warn('No paths found in the database.');
            return 1;
        }

        foreach ($get_path_lists as $row) {
            $path = $row->path;
            $dayDiff = $row->day_diff ?? 30; // Default to 30 if null
            $fullPath = public_path($path);
            $this->info("Scanning: {$fullPath} (older than {$dayDiff} days)");

            if (!file_exists($fullPath)) {
            $this->warn("Directory not found: {$fullPath}");
            continue;
            }

            $files = glob($fullPath . '/*');
            foreach ($files as $file) {
                if (is_file($file) && filemtime($file) < now()->subDays($dayDiff)->timestamp) {
                    unlink($file);
                    $this->info('Deleted: ' . $file);
                }
            }                        
        }
        
        DB::table('public_cleaners')
            ->where('remark', 1)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);

        return 0;
    }
}
