<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CheckNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix notifications table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking notifications system...');

        // Check if notifications table exists
        if (!Schema::hasTable('notifications')) {
            $this->warn('Notifications table does not exist. Creating it now...');
            
            Artisan::call('migrate', [
                '--path' => 'database/migrations/2025_04_26_115022_create_notifications_table.php'
            ]);
            
            $this->info('Notifications table created successfully.');
        } else {
            $this->info('Notifications table exists.');
            
            // Check table structure
            $columns = Schema::getColumnListing('notifications');
            $requiredColumns = ['id', 'type', 'notifiable_id', 'notifiable_type', 'data', 'read_at', 'created_at', 'updated_at'];
            
            $missingColumns = array_diff($requiredColumns, $columns);
            if (count($missingColumns) > 0) {
                $this->error('Notifications table is missing columns: ' . implode(', ', $missingColumns));
                $this->info('Recreating the notifications table...');
                
                Schema::dropIfExists('notifications');
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/2025_04_26_115022_create_notifications_table.php'
                ]);
                
                $this->info('Notifications table recreated successfully.');
            } else {
                $this->info('Notifications table structure is correct.');
            }
        }
        
        // Check queue configuration
        $queueDriver = config('queue.default');
        $this->info("Queue driver is set to: {$queueDriver}");
        
        if ($queueDriver === 'sync') {
            $this->warn('You are using the sync queue driver. For better performance, consider using database, redis, or another persistent queue driver.');
        }
        
        // Check existing notifications
        $count = DB::table('notifications')->count();
        $this->info("Total notifications in database: {$count}");
        
        // Show some sample data
        if ($count > 0) {
            $this->info('Latest notifications:');
            $latestNotifications = DB::table('notifications')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
                
            $headers = ['ID', 'Type', 'Notifiable', 'Created At', 'Read At'];
            $rows = [];
            
            foreach ($latestNotifications as $notification) {
                $rows[] = [
                    $notification->id,
                    class_basename($notification->type),
                    $notification->notifiable_type . ':' . $notification->notifiable_id,
                    $notification->created_at,
                    $notification->read_at ?: 'Unread'
                ];
            }
            
            $this->table($headers, $rows);
        }
        
        $this->info('Notification system check complete.');
    }
}