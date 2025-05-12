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
        // $path_lists = [
        //     'images/absensi',
        //     'images/driver',
        //     'images/driver_task',
        // ];

        $path_lists = DB::table('public_cleaners')
        ->where('remark', 'on')
        ->pluck('path')->toArray();

        if (empty($path_lists)) {
            $this->warn('No paths found in the database.');
            return 1;
        }

        foreach ($path_lists as $path) {
            $fullPath = public_path($path);
            $this->info("Scanning: {$fullPath}");

            if (!file_exists($fullPath)) {
                $this->warn("Directory not found: {$fullPath}");
                continue;
            }

            $files = glob($fullPath . '/*');
            foreach ($files as $file) {
                if (is_file($file) && filemtime($file) < now()->subDays(30)->timestamp) {
                    unlink($file);
                    $this->info('Deleted: ' . $file);
                }
            }
        }

        return 0;
    }
}
