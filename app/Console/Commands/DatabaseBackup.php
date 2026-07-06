<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Mail\DatabaseBackupMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:database-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a database backup and send via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reportEmail = Setting::get('report_email');
        if (empty($reportEmail)) {
            $this->error('Report email not configured in settings. Skipping database backup.');
            return;
        }

        $filename = 'backup-' . date('Y-m-d-His') . '.sql';
        $storagePath = storage_path('app/backups');
        
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $filename;

        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        // Path to mysqldump might need adjustments on Windows (e.g., C:/xampp/mysql/bin/mysqldump)
        // We'll assume it's in the PATH for now.
        $passwordArg = $dbPass ? "-p{$dbPass}" : '';
        
        $command = "mysqldump --user={$dbUser} {$passwordArg} --host={$dbHost} --port={$dbPort} {$dbName} > \"{$filePath}\"";

        try {
            // Using shell_exec to avoid Symfony Process complexity with output redirection in Windows
            shell_exec($command);
            
            if (file_exists($filePath) && filesize($filePath) > 0) {
                $this->info("Backup created successfully at {$filePath}");
                
                Mail::to($reportEmail)->send(new DatabaseBackupMail($filePath));
                $this->info("Backup queued for sending to {$reportEmail}");
                
                // Cleanup after a slight delay, or just keep it (maybe delete older than 7 days)
                // We will leave it up to the server to clean up, or we can delete after sending.
                // Note: Mail is queued, so we can't delete immediately. We'll leave the file in storage.
            } else {
                $this->error("Backup file is empty or was not created.");
            }
        } catch (\Exception $e) {
            $this->error("Failed to generate backup: " . $e->getMessage());
        }
    }
}
