<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectDeadlineApproaching;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckProjectDeadlines extends Command
{
    protected $signature = 'projects:check-deadlines';
    protected $description = 'Check for approaching project deadlines and send notifications';

    public function handle()
    {
        $today = Carbon::today();
        
        // Get projects with deadlines in the next 7 days
        $projects = Project::whereNotNull('end_date')
            ->where('status', '!=', 'Completed')
            ->where('status', '!=', 'Cancelled')
            ->where('end_date', '>', $today)
            ->where('end_date', '<=', $today->copy()->addDays(7))
            ->get();
            
        foreach ($projects as $project) {
            $daysRemaining = $today->diffInDays(Carbon::parse($project->end_date));
            
            // Notify project manager
            if ($project->manager_id) {
                $manager = User::find($project->manager_id);
                $manager->notify(new ProjectDeadlineApproaching($project, $daysRemaining));
            }
            
            // Notify team members
            foreach ($project->users as $user) {
                $user->notify(new ProjectDeadlineApproaching($project, $daysRemaining));
            }
        }
        
        $this->info('Project deadline notifications sent successfully.');
    }
}