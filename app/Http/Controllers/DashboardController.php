<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\Client;
use App\Models\Leave;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->employeeDashboard($user);
        }
    }
    
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    private function adminDashboard()
    {
        // Stats for dashboard cards
        $employeeCount = User::where('role', 'user')->count();
        $clientCount = Client::count();
        $projectCount = Project::count();
        $pendingTasks = Task::where('status', '!=', 'Completed')->count();
        
        // Get projects for projects table
        $projects = Project::with(['client', 'manager', 'users'])
                          ->orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();
                          
        // Get tasks for todo list
        $tasks = Task::where('status', '!=', 'Completed')
                    ->orderBy('priority', 'desc')
                    ->orderBy('due_date', 'asc')
                    ->take(5)
                    ->get();
        
        // Get projects with their progress
        $projectsWithProgress = Project::with(['tasks'])
                                     ->orderBy('end_date', 'asc')
                                     ->take(5)
                                     ->get()
                                     ->map(function($project) {
                                         $completedTasks = $project->tasks->where('status', 'Completed')->count();
                                         $totalTasks = $project->tasks->count();
                                         $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                         return [
                                             'name' => $project->name,
                                             'progress' => round($progress)
                                         ];
                                     })
                                     ->pluck('progress', 'name')
                                     ->toArray();
        
        // If no projects or not enough, add default ones
        if (empty($projectsWithProgress) || count($projectsWithProgress) < 5) {
            $defaultProjects = [
                'Server Migration' => 20,
                'Sales Tracking' => 40,
                'Customer Database' => 60,
                'Payout Details' => 80,
                'Account Setup' => 100
            ];
            
            $projectsWithProgress = array_merge($defaultProjects, $projectsWithProgress);
            // Limit to 5 items
            $projectsWithProgress = array_slice($projectsWithProgress, 0, 5, true);
        }
        
        // Get project statistics
        $projectStats = [
            'pending' => Project::where('status', 'Pending')->count(),
            'in_progress' => Project::where('status', 'In Progress')->count(),
            'completed' => Project::where('status', 'Completed')->count(),
            'on_hold' => Project::where('status', 'On Hold')->count(),
            'cancelled' => Project::where('status', 'Cancelled')->count(),
        ];
        
        // Get department statistics
        $departments = Department::withCount('users')->get();
        
        // Calculate earnings data for chart (based on completed projects)
        $currentYear = Carbon::now()->year;
        $earningsData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $completedProjects = Project::where('status', 'Completed')
                                      ->whereYear('updated_at', $currentYear)
                                      ->whereMonth('updated_at', $month)
                                      ->sum('budget');
            
            $earningsData[$month] = $completedProjects ?: 0;
        }
        
        // If no data, use example data
        if (array_sum($earningsData) == 0) {
            $earningsData = [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000];
        } else {
            // Convert to array values only
            $earningsData = array_values($earningsData);
        }
        
        // Calculate revenue sources for pie chart (example data)
        $revenueSources = [
            'Direct' => 55,
            'Social' => 30,
            'Referral' => 15
        ];
        
        return view('dashboard', compact(
            'employeeCount', 
            'clientCount', 
            'projectCount', 
            'pendingTasks',
            'projects',
            'tasks',
            'projectsWithProgress',
            'projectStats',
            'departments',
            'earningsData',
            'revenueSources'
        ));
    }
    
    /**
     * Display the employee dashboard.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    private function employeeDashboard(User $user)
    {
        // Get employee's tasks
        $myTasks = Task::where('assigned_to', $user->id)
                      ->where('status', '!=', 'Completed')
                      ->orderBy('priority', 'desc')
                      ->orderBy('due_date', 'asc')
                      ->get();
        
        $taskCount = Task::where('assigned_to', $user->id)->count();
        $completedTaskCount = Task::where('assigned_to', $user->id)
                                 ->where('status', 'Completed')
                                 ->count();
        $pendingTaskCount = Task::where('assigned_to', $user->id)
                               ->where('status', '!=', 'Completed')
                               ->count();
        
        // Get employee's leave information
        $leavesTaken = Leave::where('user_id', $user->id)
                           ->where('status', 'Approved')
                           ->sum('total_days');
        
        // Get default leave allowance (assuming 30 days per year)
        $leaveAllowance = 30;
        
        // Get all leave types with their counts for this employee
        $leaveTypes = DB::table('leave_types')
                        ->leftJoin('leaves', function($join) use ($user) {
                            $join->on('leave_types.id', '=', 'leaves.leave_type_id')
                                 ->where('leaves.user_id', '=', $user->id)
                                 ->where('leaves.status', '=', 'Approved');
                        })
                        ->select('leave_types.name', 'leave_types.days_allowed', 
                                 DB::raw('COALESCE(SUM(leaves.total_days), 0) as days_taken'))
                        ->groupBy('leave_types.id', 'leave_types.name', 'leave_types.days_allowed')
                        ->get();
        
        // Get employee's projects with their tasks
        $myProjects = $user->projects()
                          ->with('tasks')
                          ->orderBy('end_date', 'asc')
                          ->get();
        
        $projectCount = $myProjects->count();
        
        // Get attendance statistics
        $attendanceStats = [
            'present' => Attendance::where('user_id', $user->id)
                                 ->where('status', 'Present')
                                 ->count(),
            'absent' => Attendance::where('user_id', $user->id)
                                ->where('status', 'Absent')
                                ->count(),
            'late' => Attendance::where('user_id', $user->id)
                              ->where('status', 'Late')
                              ->count(),
        ];
        
        // Get attendance data for current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;
        
        $monthlyAttendance = Attendance::where('user_id', $user->id)
                                      ->whereMonth('date', $currentMonth)
                                      ->whereYear('date', $currentYear)
                                      ->get()
                                      ->keyBy(function($item) {
                                          return Carbon::parse($item->date)->day;
                                      });
        
        $attendanceData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day);
            if ($date->isPast()) {
                if (isset($monthlyAttendance[$day])) {
                    $attendanceData[$day] = $monthlyAttendance[$day]->status;
                } else {
                    // Skip weekends
                    if ($date->isWeekend()) {
                        $attendanceData[$day] = 'Weekend';
                    } else {
                        $attendanceData[$day] = 'No Record';
                    }
                }
            }
        }
        
        // Get task completion data by month for chart
        $taskCompletionByMonth = [];
        for ($month = 1; $month <= 12; $month++) {
            $taskCompletionByMonth[$month] = Task::where('assigned_to', $user->id)
                                               ->where('status', 'Completed')
                                               ->whereMonth('completed_date', $month)
                                               ->whereYear('completed_date', $currentYear)
                                               ->count();
        }
        
        // Convert to array values for chart
        $taskCompletionByMonth = array_values($taskCompletionByMonth);
        
        return view('dashboard', compact(
            'user',
            'myTasks',
            'taskCount',
            'completedTaskCount',
            'pendingTaskCount',
            'leavesTaken',
            'leaveAllowance',
            'leaveTypes',
            'myProjects',
            'projectCount',
            'attendanceStats',
            'attendanceData',
            'taskCompletionByMonth'
        ));
    }
}