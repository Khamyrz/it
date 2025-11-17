<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AutoExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:auto-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically export database as SQL file (runs hourly)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if auto-export is enabled
        $configPath = config_path('auto_export.php');
        $enabled = false;
        
        if (file_exists($configPath)) {
            $config = require $configPath;
            $enabled = $config['enabled'] ?? false;
        }
        
        if (!$enabled) {
            $this->info('Auto-export is disabled. Skipping export.');
            return 0;
        }
        
        $this->info('Starting automatic database export...');
        
        try {
            $controller = new CategoryController();
            $response = $controller->exportSql();
            
            // Get the SQL content from response
            $sqlContent = $response->getContent();
            
            // Ensure exports directory exists
            if (!Storage::disk('local')->exists('exports')) {
                Storage::disk('local')->makeDirectory('exports');
            }
            
            // Save to storage/exports directory
            $filename = 'database_export_' . date('Y-m-d_His') . '.sql';
            $path = 'exports/' . $filename;
            
            Storage::disk('local')->put($path, $sqlContent);
            
            $this->info("Database exported successfully to: storage/app/{$path}");
            
            // Keep only last 24 exports (one per hour for 24 hours)
            $this->cleanupOldExports();
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Export failed: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Clean up old export files (keep only last 24)
     */
    private function cleanupOldExports()
    {
        $files = Storage::disk('local')->files('exports');
        
        // Sort by modification time (newest first)
        usort($files, function($a, $b) {
            return Storage::disk('local')->lastModified($b) - Storage::disk('local')->lastModified($a);
        });
        
        // Keep only the 24 most recent files
        if (count($files) > 24) {
            $filesToDelete = array_slice($files, 24);
            foreach ($filesToDelete as $file) {
                Storage::disk('local')->delete($file);
                $this->info("Deleted old export: {$file}");
            }
        }
    }
}

